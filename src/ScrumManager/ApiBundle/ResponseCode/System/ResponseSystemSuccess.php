<?php

namespace ScrumManager\ApiBundle\ResponseCode\System;


use ScrumManager\ApiBundle\ResponseCode\BaseResponseCode;

class ResponseSystemSuccess extends BaseResponseCode {

    public static $message = 'Request was successful';
    public static $code = 100;
}