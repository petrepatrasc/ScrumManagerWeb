<?php

namespace ScrumManager\ApiBundle\Tests\Unit;

use \DateTime;
use ScrumManager\ApiBundle\Entity\Account;

/**
 * Class containing unit tests for the Account entity.
 * @package ScrumManager\ApiBundle\Tests\Unit
 */
class AccountTest extends BaseUnitTestCase {

    /**
     * For this test, we want to just run a happy case of the method and see that we in fact get an object back
     * that is consistent with our expectations.
     */
    public function testMakeFromArray_ValidAllFields() {
        $data = array(
            'username' => $this->generateRandomString(10),
            'password' => $this->generateRandomString(128),
            'seed' => $this->generateRandomString(16),
            'first_name' => $this->generateRandomString(60),
            'last_name' => $this->generateRandomString(60),
            'email' => $this->generateRandomString(120),
            'api_key' => $this->generateRandomString(128),
            'reset_token' => $this->generateRandomString(128),
            'reset_initiated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        $account = Account::makeFromArray($data);

        $this->assertEquals($account->getUsername(), $data['username']);
        $this->assertEquals($account->getPassword(), $data['password']);
        $this->assertEquals($account->getSeed(), $data['seed']);
        $this->assertEquals($account->getFirstName(), $data['first_name']);
        $this->assertEquals($account->getLastName(), $data['last_name']);
        $this->assertEquals($account->getEmail(), $data['email']);
        $this->assertEquals($account->getApiKey(), $data['api_key']);
        $this->assertEquals($account->getResetToken(), $data['reset_token']);
        $this->assertEquals($account->getResetInitiatedAt(), new DateTime($data['reset_initiated_at']));
        $this->assertEquals($account->getCreatedAt(), new DateTime($data['created_at']));
        $this->assertEquals($account->getUpdatedAt(), new DateTime($data['updated_at']));
    }

    /**
     * Test the method that transforms an entity into an array. Our plan here is to generate a new entity,
     * then transform it to an array and see if the values match after processing.
     */
    public function testToArray_ValidAllFields() {
        $data = array(
            'username' => $this->generateRandomString(10),
            'password' => $this->generateRandomString(128),
            'seed' => $this->generateRandomString(16),
            'first_name' => $this->generateRandomString(60),
            'last_name' => $this->generateRandomString(60),
            'email' => $this->generateRandomString(120),
            'api_key' => $this->generateRandomString(128),
            'reset_token' => $this->generateRandomString(128),
            'reset_initiated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        $account = new Account();
        $account->setUsername($data['username']);
        $account->setPassword($data['password']);
        $account->setSeed($data['seed']);
        $account->setFirstName($data['first_name']);
        $account->setLastName($data['last_name']);
        $account->setEmail($data['email']);
        $account->setApiKey($data['api_key']);
        $account->setResetToken($data['reset_token']);
        $account->setResetInitiatedAt(new DateTime($data['reset_initiated_at']));
        $account->setCreatedAt(new DateTime($data['created_at']));
        $account->setUpdatedAt(new DateTime($data['updated_at']));

        $arrayObject = $account->toArray();

        $this->assertEquals($arrayObject['username'], $account->getUsername());
        $this->assertEquals($arrayObject['password'], $account->getPassword());
        $this->assertEquals($arrayObject['seed'], $account->getSeed());
        $this->assertEquals($arrayObject['first_name'], $account->getFirstName());
        $this->assertEquals($arrayObject['last_name'], $account->getLastName());
        $this->assertEquals($arrayObject['email'], $account->getEmail());
        $this->assertEquals($arrayObject['api_key'], $account->getApiKey());
        $this->assertEquals($arrayObject['reset_token'], $account->getResetToken());
        $this->assertEquals(new DateTime($arrayObject['reset_initiated_at']), $account->getResetInitiatedAt());
        $this->assertEquals(new DateTime($arrayObject['created_at']), $account->getCreatedAt());
        $this->assertEquals(new DateTime($arrayObject['updated_at']), $account->getUpdatedAt());
    }

    /**
     * In this test, we want to also use the second, optional parameter of the method, and try to retrieve
     * the data from an array into another entity.
     */
    public function testMakeFromArray_RetrieveDataInDifferentEntity() {
        $data = array(
            'username' => $this->generateRandomString(10),
            'password' => $this->generateRandomString(128),
            'seed' => $this->generateRandomString(16),
            'first_name' => $this->generateRandomString(60),
            'last_name' => $this->generateRandomString(60),
            'email' => $this->generateRandomString(120),
            'api_key' => $this->generateRandomString(128),
            'reset_token' => null,
            'reset_initiated_at' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        $account = new Account();
        $account->setUsername($data['username']);
        $account->setPassword($data['password']);
        $account->setSeed($data['seed']);
        $account->setFirstName($data['first_name']);
        $account->setLastName($data['last_name']);
        $account->setEmail($data['email']);
        $account->setApiKey($data['api_key']);
        $account->setResetToken($data['reset_token']);
        $account->setResetInitiatedAt(new DateTime($data['reset_initiated_at']));
        $account->setCreatedAt(new DateTime($data['created_at']));
        $account->setUpdatedAt(new DateTime($data['updated_at']));

        $account = Account::makeFromArray($data, $account);

        $this->assertEquals($account->getUsername(), $data['username']);
        $this->assertEquals($account->getPassword(), $data['password']);
        $this->assertEquals($account->getSeed(), $data['seed']);
        $this->assertEquals($account->getFirstName(), $data['first_name']);
        $this->assertEquals($account->getLastName(), $data['last_name']);
        $this->assertEquals($account->getEmail(), $data['email']);
        $this->assertEquals($account->getApiKey(), $data['api_key']);
        $this->assertEquals($account->getResetToken(), $data['reset_token']);
        $this->assertEquals($account->getResetInitiatedAt(), new DateTime($data['reset_initiated_at']));
        $this->assertEquals($account->getCreatedAt(), new DateTime($data['created_at']));
        $this->assertEquals($account->getUpdatedAt(), new DateTime($data['updated_at']));
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