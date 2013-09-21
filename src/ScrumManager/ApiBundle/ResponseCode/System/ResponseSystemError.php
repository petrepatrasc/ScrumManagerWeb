<?php

namespace ScrumManager\ApiBundle\ResponseCode\System;


use ScrumManager\ApiBundle\ResponseCode\BaseResponseCode;

class ResponseSystemError extends BaseResponseCode {

    public static $message = 'An error occurred!';
    public static $code = 103;
}