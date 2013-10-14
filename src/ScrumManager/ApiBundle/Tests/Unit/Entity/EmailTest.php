<?php

namespace ScrumManager\ApiBundle\Tests\Unit;


use ScrumManager\ApiBundle\Entity\Email;
use DateTime;
use ScrumManager\ApiBundle\Service\GeneralHelperService;

class EmailTest extends BaseUnitTestCase {

    protected $constructionParameters;

    public function setUp() {
        $this->constructionParameters = array(
            'sender' => GeneralHelperService::generateRandomString(20),
            'receiver' => GeneralHelperService::generateRandomString(20),
            'subject' => GeneralHelperService::generateRandomString(100),
            'content' => GeneralHelperService::generateRandomString(620),
            'read' => false,
            'sent' => false,
            'active' => false
        );
    }

    /**
     * Test that when the data that is provided to the make from array is correct, the
     * method will act accordingly.
     */
    public function testMakeFromArray_Valid() {
        $email = Email::makeFromArray($this->constructionParameters);

        $this->assertNotNull($email);
        $this->assertEquals($this->constructionParameters['sender'], $email->getSender());
        $this->assertEquals($this->constructionParameters['receiver'], $email->getReceiver());
        $this->assertEquals($this->constructionParameters['subject'], $email->getSubject());
        $this->assertEquals($this->constructionParameters['content'], $email->getContent());
        $this->assertEquals($this->constructionParameters['read'], $email->getRead());
        $this->assertEquals($this->constructionParameters['sent'], $email->getSent());
        $this->assertEquals($this->constructionParameters['active'], $email->getActive());
        $this->assertNotNull($email->getCreatedAt());
        $this->assertNotNull($email->getUpdatedAt());
    }

    /**
     * Test that when the data that is provided to the make from array is correct, the
     * method will act accordingly.
     */
    public function testMakeFromArray_ValidAlsoWithDateTime() {
        $this->constructionParameters['created_at'] = date('Y-m-d H:i:s');
        $this->constructionParameters['updated_at'] = date('Y-m-d H:i:s');
        $email = Email::makeFromArray($this->constructionParameters);

        $this->assertNotNull($email);
        $this->assertEquals($this->constructionParameters['sender'], $email->getSender());
        $this->assertEquals($this->constructionParameters['receiver'], $email->getReceiver());
        $this->assertEquals($this->constructionParameters['subject'], $email->getSubject());
        $this->assertEquals($this->constructionParameters['content'], $email->getContent());
        $this->assertEquals($this->constructionParameters['read'], $email->getRead());
        $this->assertEquals($this->constructionParameters['sent'], $email->getSent());
        $this->assertEquals($this->constructionParameters['active'], $email->getActive());
        $this->assertEquals(new DateTime($this->constructionParameters['created_at']), $email->getCreatedAt());
        $this->assertEquals(new DateTime($this->constructionParameters['updated_at']), $email->getUpdatedAt());
    }

    /**
     * Test the method of transforming an entity into an array, when the data stored within is valid.
     */
    public function testToArray_Valid() {
        $email = $this->createNewEntityUsingConstructionParameters($this->constructionParameters);
        $arrayFromEntity = $email->toArray();

        $this->assertEquals($this->constructionParameters['sender'], $arrayFromEntity['sender']);
        $this->assertEquals($this->constructionParameters['receiver'], $arrayFromEntity['receiver']);
        $this->assertEquals($this->constructionParameters['subject'], $arrayFromEntity['subject']);
        $this->assertEquals($this->constructionParameters['content'], $arrayFromEntity['content']);
        $this->assertEquals($this->constructionParameters['read'], $arrayFromEntity['read']);
        $this->assertEquals($this->constructionParameters['sent'], $arrayFromEntity['sent']);
        $this->assertEquals($this->constructionParameters['active'], $arrayFromEntity['active']);
        $this->assertNotNull($email->getCreatedAt());
        $this->assertNotNull($email->getUpdatedAt());
    }

    /**
     * Test the method of transforming an entity into an array that is safe for API transmission, when the data
     * stored within is valid.
     */
    public function testToSafeArray_Valid() {
        $email = $this->createNewEntityUsingConstructionParameters($this->constructionParameters);
        $arrayFromEntity = $email->toSafeArray();

        $this->assertEquals($this->constructionParameters['sender'], $arrayFromEntity['sender']);
        $this->assertEquals($this->constructionParameters['receiver'], $arrayFromEntity['receiver']);
        $this->assertEquals($this->constructionParameters['subject'], $arrayFromEntity['subject']);
        $this->assertEquals($this->constructionParameters['content'], $arrayFromEntity['content']);
        $this->assertEquals($this->constructionParameters['read'], $arrayFromEntity['read']);
        $this->assertEquals($this->constructionParameters['sent'], $arrayFromEntity['sent']);
        $this->assertNotNull($email->getCreatedAt());
        $this->assertNotNull($email->getUpdatedAt());
    }

    /**
     * Create a new entity using the construction parameters.
     * @param array $constructionParameters The array with which to construct the object.
     * @return Email The email entity that has been constructed from the random parameters for each test.
     */
    protected function createNewEntityUsingConstructionParameters(array $constructionParameters) {
        $email = new Email();

        $email->setSender($constructionParameters['sender']);
        $email->setReceiver($constructionParameters['receiver']);
        $email->setSubject($constructionParameters['subject']);
        $email->setContent($constructionParameters['content']);
        $email->setSent($constructionParameters['sent']);
        $email->setRead($constructionParameters['read']);
        $email->setActive($constructionParameters['active']);

        return $email;
    }
}