<?php

namespace ScrumManager\ApiBundle\Tests\Unit;

use \DateTime;
use ScrumManager\ApiBundle\Entity\Account;
use ScrumManager\ApiBundle\Service\GeneralHelperService;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class containing unit tests for the Account entity.
 * @package ScrumManager\ApiBundle\Tests\Unit
 */
class AccountTest extends BaseUnitTestCase {

    /**
     * @var array
     */
    protected $seedData;

    public function setUp() {
        parent::setUp();

        $this->seedData = array(
            'username' => GeneralHelperService::generateRandomString(10),
            'password' => GeneralHelperService::generateRandomString(128),
            'seed' => GeneralHelperService::generateRandomString(16),
            'firstName' => GeneralHelperService::generateRandomString(60),
            'lastName' => GeneralHelperService::generateRandomString(60),
            'email' => GeneralHelperService::generateRandomString(120),
            'apiKey' => GeneralHelperService::generateRandomString(128),
            'resetToken' => GeneralHelperService::generateRandomString(128),
            'resetInitiatedAt' => new DateTime('now'),
            'createdAt' => new DateTime('now'),
            'updatedAt' => new DateTime('now')
        );
    }

    /**
     * For this test, we want to just run a happy case of the method and see that we in fact get an object back
     * that is consistent with our expectations.
     */
    public function testMakeFromArray_ValidAllFields() {
        $account = $this->serializer->denormalize($this->seedData, 'ScrumManager\ApiBundle\Entity\Account');
        $this->assertAllValuesBetweenEntityAndArray($account, $this->seedData);
    }

    /**
     * Assert that the values held within an entity as the same held within a normalised array.
     * @param Account $entity The entity which we want to check against
     * @param array $data The array which we want to check
     */
    protected function assertAllValuesBetweenEntityAndArray(Account $entity, $data) {
        $this->assertEquals($entity->getUsername(), $data['username']);
        $this->assertEquals($entity->getPassword(), $data['password']);
        $this->assertEquals($entity->getSeed(), $data['seed']);
        $this->assertEquals($entity->getFirstName(), $data['firstName']);
        $this->assertEquals($entity->getLastName(), $data['lastName']);
        $this->assertEquals($entity->getEmail(), $data['email']);
        $this->assertEquals($entity->getApiKey(), $data['apiKey']);
        $this->assertEquals($entity->getResetToken(), $data['resetToken']);
        $this->assertEquals($entity->getResetInitiatedAt(), $data['resetInitiatedAt']);
//        $this->assertEquals($entity->getCreatedAt(), $data['createdAt']);
//        $this->assertEquals($entity->getUpdatedAt(), $data['updatedAt']);
    }

    /**
     * Create a new entity based on the keys within an array.
     * @param array $data The array which should be used for generating the entity.
     * @return Account The entity which results from generation.
     */
    protected function createEntityFromArrayKeys($data) {
        $account = new Account();

        $account->setUsername($data['username']);
        $account->setPassword($data['password']);
        $account->setSeed($data['seed']);
        $account->setFirstName($data['firstName']);
        $account->setLastName($data['lastName']);
        $account->setEmail($data['email']);
        $account->setApiKey($data['apiKey']);
        $account->setResetToken($data['resetToken']);
        $account->setResetInitiatedAt($data['resetInitiatedAt']);
        $account->setCreatedAt($data['createdAt']);
        $account->setUpdatedAt($data['updatedAt']);

        return $account;
    }

    /**
     * Test the method that transforms an entity into an array. Our plan here is to generate a new entity,
     * then transform it to an array and see if the values match after processing.
     */
    public function testToArray_ValidAllFields() {
        $account = $this->createEntityFromArrayKeys($this->seedData);
        $arrayObject = $this->serializer->normalize($account);

        $resetInitiatedAt = new DateTime(strtotime($arrayObject['resetInitiatedAt']['timestamp']));
        $createdAt = new DateTime(strtotime($arrayObject['createdAt']['timestamp']));
        $updatedAt = new DateTime(strtotime($arrayObject['updatedAt']['timestamp']));

        $this->assertEquals($arrayObject['username'], $account->getUsername());
        $this->assertEquals($arrayObject['password'], $account->getPassword());
        $this->assertEquals($arrayObject['seed'], $account->getSeed());
        $this->assertEquals($arrayObject['firstName'], $account->getFirstName());
        $this->assertEquals($arrayObject['lastName'], $account->getLastName());
        $this->assertEquals($arrayObject['email'], $account->getEmail());
        $this->assertEquals($arrayObject['apiKey'], $account->getApiKey());
        $this->assertEquals($arrayObject['resetToken'], $account->getResetToken());
//        $this->assertEquals($resetInitiatedAt, $account->getResetInitiatedAt());
//        $this->assertEquals($createdAt, $account->getCreatedAt());
//        $this->assertEquals($updatedAt, $account->getUpdatedAt());
    }

    /**
     * Our test here is just to check that in the event that we create a new entity, some default values are
     * automatically applied to it.
     */
    public function testEntityConstraints_EnsureDefaultsHold() {
        $account = new Account();

        $this->assertNull($account->getUsername());
        $this->assertNull($account->getPassword());
        $this->assertNull($account->getSeed());
        $this->assertNull($account->getFirstName());
        $this->assertNull($account->getLastName());
        $this->assertNull($account->getEmail());
        $this->assertNull($account->getApiKey());
        $this->assertNull($account->getResetToken());
        $this->assertNull($account->getResetInitiatedAt());
        $this->assertNotNull($account->getCreatedAt());
        $this->assertNotNull($account->getUpdatedAt());
    }
}