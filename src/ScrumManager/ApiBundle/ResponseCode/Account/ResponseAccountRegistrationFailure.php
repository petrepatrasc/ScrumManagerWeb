<?php

namespace ScrumManager\ApiBundle\ResponseCode\Account;


use ScrumManager\ApiBundle\ResponseCode\BaseResponseCode;

class ResponseAccountRegistrationFailure extends BaseResponseCode {

    public static $message = 'Registration incorrect - validation failed';
    public static $code = 202;
}