<?php

namespace ScrumManager\ApiBundle\ResponseCode\Account;


use ScrumManager\ApiBundle\ResponseCode\BaseResponseCode;

class ResponseAccountInvalidCredentials extends BaseResponseCode {

    public static $message = 'account.credentials.invalid';
    public static $code = 203;
}