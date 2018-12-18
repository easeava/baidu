<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\BearTP\Authorizer\Bear\Base;

use EaseBaidu\Kernel\BaseClient;

class Client extends BaseClient
{

    public function getAuthorizer()
    {
        return $this->httpGet('/rest/2.0/cambrian/tp/api_get_authorizer_info');
    }
}