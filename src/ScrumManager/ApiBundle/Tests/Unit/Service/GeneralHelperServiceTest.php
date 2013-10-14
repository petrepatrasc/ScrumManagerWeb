<?php
/**
 * Created by JetBrains PhpStorm.
 * User: petre
 * Date: 10/14/13
 * Time: 6:01 PM
 * To change this template use File | Settings | File Templates.
 */

namespace ScrumManager\ApiBundle\Tests\Integration;


use ScrumManager\ApiBundle\Service\GeneralHelperService;
use ScrumManager\ApiBundle\Tests\Unit\BaseUnitTestCase;

class GeneralHelperServiceTest extends BaseUnitTestCase {

    public function testGenerateRandomString_RandomLength() {
        $randomLength = rand(1, 100);
        $string = GeneralHelperService::generateRandomString($randomLength);

        $this->assertEquals($randomLength, strlen($string));
    }

    public function testGenerateRandomString_NoParameters() {
        $string = GeneralHelperService::generateRandomString();

        $this->assertGreaterThan(0, strlen($string));
    }
}