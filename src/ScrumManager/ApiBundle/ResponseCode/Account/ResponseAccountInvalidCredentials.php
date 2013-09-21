<?php

namespace ScrumManager\ApiBundle\ResponseCode\Account;


use ScrumManager\ApiBundle\ResponseCode\BaseResponseCode;

class ResponseAccountInvalidCredentials extends BaseResponseCode {

    public static $message = 'Invalid Credentials';
    public static $code = 203;
}