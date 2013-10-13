<?php

namespace ScrumManager\ApiBundle\ResponseCode\Email;


use ScrumManager\ApiBundle\ResponseCode\BaseResponseCode;

class ResponseEmailRetrieveFailure extends BaseResponseCode{

    public static $message = 'email.retrieve.failure';
    public static $code = 302;
}