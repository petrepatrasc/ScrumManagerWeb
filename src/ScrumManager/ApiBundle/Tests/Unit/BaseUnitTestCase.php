<?php

namespace ScrumManager\ApiBundle\Tests\Unit;

use \PHPUnit_Framework_TestCase;
use ScrumManager\ApiBundle\Service\GeneralHelperService;
use Symfony\Component\Serializer\Serializer;

class BaseUnitTestCase extends PHPUnit_Framework_TestCase {

    /**
     * @var Serializer
     */
    protected $serializer;

    public function __construct() {
        $this->serializer = GeneralHelperService::getDefaultSerializer();
    }
}