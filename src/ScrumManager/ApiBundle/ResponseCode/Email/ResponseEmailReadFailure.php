<?php

namespace ScrumManager\ApiBundle\ResponseCode\Email;


use ScrumManager\ApiBundle\ResponseCode\BaseResponseCode;

class ResponseEmailReadFailure extends BaseResponseCode{

    public static $message = 'email.read.failure';
    public static $code = 303;
}