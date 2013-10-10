<?php

namespace ScrumManager\ApiBundle\ResponseCode\Email;


use ScrumManager\ApiBundle\ResponseCode\BaseResponseCode;

class ResponseEmailRetrieveFailure extends BaseResponseCode{

    public static $message = 'Retrieving an email failed';
    public static $code = 302;
}