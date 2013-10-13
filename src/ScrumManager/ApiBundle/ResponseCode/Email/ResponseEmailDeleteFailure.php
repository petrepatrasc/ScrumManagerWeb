<?php

namespace ScrumManager\ApiBundle\ResponseCode\Email;


use ScrumManager\ApiBundle\ResponseCode\BaseResponseCode;

class ResponseEmailDeleteFailure extends BaseResponseCode {


    public static $message = 'email.delete.failure';
    public static $code = 304;
}