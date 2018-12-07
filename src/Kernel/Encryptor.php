<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Kernel;

use EaseBaidu\Kernel\Exceptions\RuntimeException;
use EaseBaidu\Kernel\Support\AES;
use EaseBaidu\Kernel\Support\XML;
use Throwable;
use Illuminate\Support\Str;

class Encryptor
{
    const ERROR_INVALID_SIGNATURE = -40001; // Signature verification failed
    const ERROR_PARSE_XML = -40002; // Parse XML failed
    const ERROR_CALC_SIGNATURE = -40003; // Calculating the signature failed
    const ERROR_INVALID_AES_KEY = -40004; // Invalid AESKey
    const ERROR_INVALID_CLIENT_ID = -40005; // Check AppID failed
    const ERROR_ENCRYPT_AES = -40006; // AES EncryptionInterface failed
    const ERROR_DECRYPT_AES = -40007; // AES decryption failed
    const ERROR_INVALID_XML = -40008; // Invalid XML
    const ERROR_BASE64_ENCODE = -40009; // Base64 encoding failed
    const ERROR_BASE64_DECODE = -40010; // Base64 decoding failed
    const ERROR_XML_BUILD = -40011; // XML build failed
    const ILLEGAL_BUFFER = -41003; // Illegal buffer

    /**
     * @var string
     */
    protected $clientID;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var bool|string
     */
    protected $aesKey;

    /**
     * @var int
     */
    protected $blockSize = 32;

    /**
     * Encryptor constructor.
     *
     * @param string $clientID
     * @param string|null $token
     * @param string|null $aesKey
     */
    public function __construct(string $clientID, string $token = null, string $aesKey = null)
    {
        $this->clientID = $clientID;
        $this->token = $token;
        $this->aesKey = base64_decode($aesKey.'=', true);
    }

    /**
     * Get the app token.
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Encrypt the message and return XML.
     * 
     * @param $xml
     * @param null $nonce
     * @param null $timestamp
     * @return string
     * @throws RuntimeException
     */
    public function encrypt($xml, $nonce = null, $timestamp = null): string
    {
        try {
            $xml = $this->encode(Str::random(16).pack('N', strlen($xml)).$xml.$this->clientID, $this->blockSize);

            $encrypted = base64_encode(AES::encrypt(
                $xml,
                $this->aesKey,
                substr($this->aesKey, 0, 16),
                OPENSSL_NO_PADDING
            ));
            // @codeCoverageIgnoreStart
        } catch (Throwable $e) {
            throw new RuntimeException($e->getMessage(), self::ERROR_ENCRYPT_AES);
        }
        // @codeCoverageIgnoreEnd

        ! is_null($nonce) || $nonce = substr($this->clientID, 0, 10);
        ! is_null($timestamp) || $timestamp = time();

        $response = [
            'Encrypt' => $encrypted,
            'MsgSignature' => $this->signature($this->token, $timestamp, $nonce, $encrypted),
            'TimeStamp' => $timestamp,
            'Nonce' => $nonce,
        ];

        //生成响应xml
        return XML::build($response);
    }

    /**
     * Decrypt message.
     *
     * @param $content
     * @param $msgSignature
     * @param $nonce
     * @param $timestamp
     * @return string
     * @throws RuntimeException
     */
    public function decrypt($content, $msgSignature, $nonce, $timestamp): string
    {
        $signature = $this->signature($this->token, $timestamp, $nonce, $content);

        if ($signature !== $msgSignature) {
            throw new RuntimeException('Invalid Signature.', self::ERROR_INVALID_SIGNATURE);
        }

        $decrypted = AES::decrypt(
            base64_decode($content, true),
            $this->aesKey,
            substr($this->aesKey, 0, 16),
            OPENSSL_NO_PADDING
        );

        $result = $this->decode($decrypted);
        $content = substr($result, 16, strlen($result));
        $contentLen = unpack('N', substr($content, 0, 4))[1];

        if (trim(substr($content, $contentLen + 4)) !== $this->clientID) {
            throw new RuntimeException('Invalid clientId.', self::ERROR_INVALID_CLIENT_ID);
        }

        return substr($content, 4, $contentLen);
    }

    /**
     * Get SHA1.
     *
     * @return string
     */
    public function signature(): string
    {
        $array = func_get_args();
        sort($array, SORT_STRING);

        return sha1(implode($array));
    }

    /**
     * @param string $text
     * @param int $blockSize
     * @return string
     * @throws RuntimeException
     */
    public function encode(string $text, int $blockSize): string
    {
        if ($blockSize > 256) {
            throw new RuntimeException('$blockSize may not be more than 256');
        }
        $padding = $blockSize - (strlen($text) % $blockSize);
        $pattern = chr($padding);

        return $text.str_repeat($pattern, $padding);
    }

    /**
     * @param string $text
     * @return string
     */
    public function decode(string $text): string
    {
        $pad = ord(substr($text, -1));
        if ($pad < 1 || $pad > $this->blockSize) {
            $pad = 0;
        }

        return substr($text, 0, (strlen($text) - $pad));
    }
}