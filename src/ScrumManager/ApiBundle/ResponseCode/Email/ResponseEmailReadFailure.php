<?php

namespace ScrumManager\ApiBundle\ResponseCode\Email;


use ScrumManager\ApiBundle\ResponseCode\BaseResponseCode;

class ResponseEmailReadFailure extends BaseResponseCode{

    public static $message = 'Marking an email as read failed';
    public static $code = 303;
}