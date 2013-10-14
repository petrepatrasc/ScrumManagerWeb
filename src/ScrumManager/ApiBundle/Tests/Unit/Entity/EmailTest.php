<?php

namespace ScrumManager\ApiBundle\Tests\Unit;


use ScrumManager\ApiBundle\Entity\Email;
use DateTime;
use ScrumManager\ApiBundle\Service\GeneralHelperService;

class EmailTest extends BaseUnitTestCase {

    /**
     * @var array
     */
    protected $seedData;

    public function setUp() {
        $this->seedData = array(
            'sender' => GeneralHelperService::generateRandomString(20),
            'receiver' => GeneralHelperService::generateRandomString(20),
            'subject' => GeneralHelperService::generateRandomString(100),
            'content' => GeneralHelperService::generateRandomString(620),
            'read' => false,
            'sent' => false,
            'active' => false,
            'createdAt' => new DateTime('now'),
            'updatedAt' => new DateTime('now')
        );
    }

    /**
     * Assert that all the values match between a normalized array and an entity.
     * @param array $data The data array which we are checking against.
     * @param Email $entity The entity which we are checking.
     */
    protected function assertAllValuesBetweenArrayAndEntity($data, Email $entity) {
        $this->assertNotNull($entity);
        $this->assertEquals($this->seedData['sender'], $entity->getSender());
        $this->assertEquals($this->seedData['receiver'], $entity->getReceiver());
        $this->assertEquals($this->seedData['subject'], $entity->getSubject());
        $this->assertEquals($this->seedData['content'], $entity->getContent());
        $this->assertEquals($this->seedData['read'], $entity->getRead());
        $this->assertEquals($this->seedData['sent'], $entity->getSent());
        $this->assertEquals($this->seedData['active'], $entity->getActive());
//        $this->assertEquals($this->seedData['createdAt'], $entity->getCreatedAt());
//        $this->assertEquals($this->seedData['updatedAt'], $entity->getUpdatedAt());
    }

    /**
     * Test that when the data that is provided to the make from array is correct, the
     * method will act accordingly.
     */
    public function testMakeFromArray_Valid() {
        $email = $this->serializer->denormalize($this->seedData, 'ScrumManager\ApiBundle\Entity\Email');
        $this->assertNotNull($email);
        $this->assertAllValuesBetweenArrayAndEntity($this->seedData, $email);
    }


    /**
     * Test the method of transforming an entity into an array, when the data stored within is valid.
     */
    public function testToArray_Valid() {
        $email = $this->createNewEntityUsingConstructionParameters($this->seedData);
        $arrayFromEntity = $this->serializer->normalize($email);

        $arrayFromEntity['createdAt'] = new DateTime(strtotime($arrayFromEntity['createdAt']['timestamp']));
        $arrayFromEntity['updatedAt'] = new DateTime(strtotime($arrayFromEntity['updatedAt']['timestamp']));
        unset($arrayFromEntity['id']);

        $this->assertEquals($this->seedData, $arrayFromEntity);
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
        $email->setCreatedAt($constructionParameters['createdAt']);
        $email->setUpdatedAt($constructionParameters['updatedAt']);

        return $email;
    }
}