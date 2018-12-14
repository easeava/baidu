<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\SmartProgram;

use EaseBaidu\Kernel\Exceptions\DecryptException;
use EaseBaidu\Kernel\Support\AES;

class Encryptor extends \EaseBaidu\Kernel\Encryptor
{
    /**
     * Decrypt data.
     * 详见 https://smartprogram.baidu.com/docs/develop/api/open_log/#%E7%94%A8%E6%88%B7%E6%95%B0%E6%8D%AE%E7%9A%84%E7%AD%BE%E5%90%8D%E9%AA%8C%E8%AF%81%E5%92%8C%E5%8A%A0%E8%A7%A3%E5%AF%86/
     *
     * @param $ciphertext
     * @param $iv
     * @param $session_key
     * @return mixed|string
     * @throws DecryptException
     */
    public function decryptData($ciphertext, $iv, $session_key)
    {
        $decrypted = AES::decrypt(
            base64_decode($ciphertext, false), base64_decode($session_key, false), base64_decode($iv, false)
        );

        $decrypted = json_decode($this->decode($decrypted), true);

        if (! $decrypted) {
            throw new DecryptException('The given payload is invalid.');
        }

        return $decrypted;
    }
}