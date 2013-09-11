<?php

namespace ScrumManager\ApiBundle\Tests\Integration;


use ScrumManager\ApiBundle\Entity\Account;
use ScrumManager\ApiBundle\Repository\AccountRepository;
use \DateTime;

class AccountRepositoryTest extends BaseIntegrationTestCase {

    /**
     * @var AccountRepository
     */
    protected $repo;

    public function setUp() {
        parent::setUp();
        $this->repo = $this->em->getRepository('ScrumManagerApiBundle:Account');
    }

    public function tearDown() {
        parent::tearDown();
        unset($this->repo);
    }

    /**
     * Test the create method with valid data.
     */
    public function testCreate_Valid() {
        $data = array(
            'username' => $this->generateRandomString(10),
            'password' => $this->generateRandomString(128),
            'seed' => $this->generateRandomString(20),
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
        $account->setPassword(hash('sha512', $data['seed'] . $data['password']));
        $account->setSeed($data['seed']);
        $account->setFirstName($data['first_name']);
        $account->setLastName($data['last_name']);
        $account->setEmail($data['email']);
        $account->setApiKey($data['api_key']);
        $account->setResetToken($data['reset_token']);
        $account->setResetInitiatedAt(new DateTime($data['reset_initiated_at']));
        $account->setCreatedAt(new DateTime($data['created_at']));
        $account->setUpdatedAt(new DateTime($data['updated_at']));

        $this->assertNull($account->getId());

        $account = $this->repo->create($account);

        $this->assertNotNull($account->getId());
        $this->assertEquals($data['username'], $account->getUsername());
        $this->assertEquals(hash('sha512', $data['seed'] . $data['password']), $account->getPassword());
        $this->assertEquals($data['seed'], $account->getSeed());
        $this->assertEquals($data['first_name'], $account->getFirstName());
        $this->assertEquals($data['last_name'], $account->getLastName());
        $this->assertEquals($data['email'], $account->getEmail());
        $this->assertEquals($data['api_key'], $account->getApiKey());
        $this->assertEquals($data['reset_token'], $account->getResetToken());
        $this->assertEquals(new DateTime($data['reset_initiated_at']), $account->getResetInitiatedAt());
        $this->assertEquals(new DateTime($data['created_at']), $account->getCreatedAt());
        $this->assertEquals(new DateTime($data['updated_at']), $account->getUpdatedAt());
    }

    /**
     * Test the login method with valid data.
     */
    public function testLogin_Valid() {
        $data = array(
            'username' => $this->generateRandomString(10),
            'password' => $this->generateRandomString(70),
            'seed' => $this->generateRandomString(20),
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
        $account->setPassword(hash('sha512', $data['seed'] . $data['password']));
        $account->setSeed($data['seed']);
        $account->setFirstName($data['first_name']);
        $account->setLastName($data['last_name']);
        $account->setEmail($data['email']);
        $account->setApiKey($data['api_key']);
        $account->setResetToken($data['reset_token']);
        $account->setResetInitiatedAt(new DateTime($data['reset_initiated_at']));
        $account->setCreatedAt(new DateTime($data['created_at']));
        $account->setUpdatedAt(new DateTime($data['updated_at']));

        $this->assertNull($account->getId());

        $account = $this->repo->create($account);

        $this->assertNotNull($account->getId());
        $this->assertEquals($data['username'], $account->getUsername());
        $this->assertEquals(hash('sha512', $data['seed'] . $data['password']), $account->getPassword());
        $this->assertEquals($data['seed'], $account->getSeed());
        $this->assertEquals($data['first_name'], $account->getFirstName());
        $this->assertEquals($data['last_name'], $account->getLastName());
        $this->assertEquals($data['email'], $account->getEmail());
        $this->assertEquals($data['api_key'], $account->getApiKey());
        $this->assertEquals($data['reset_token'], $account->getResetToken());
        $this->assertEquals(new DateTime($data['reset_initiated_at']), $account->getResetInitiatedAt());
        $this->assertEquals(new DateTime($data['created_at']), $account->getCreatedAt());
        $this->assertEquals(new DateTime($data['updated_at']), $account->getUpdatedAt());

        $accountWithLogin = $this->repo->findByUsernameAndPassword($data['username'], $data['password'], $data['seed']);

        $this->assertNotNull($accountWithLogin);
        $this->assertNotNull($accountWithLogin->getId());
        $this->assertEquals($data['username'], $accountWithLogin->getUsername());
        $this->assertEquals(hash('sha512', $data['seed'] . $data['password']), $accountWithLogin->getPassword());
        $this->assertEquals($data['seed'], $accountWithLogin->getSeed());
        $this->assertEquals($data['first_name'], $accountWithLogin->getFirstName());
        $this->assertEquals($data['last_name'], $accountWithLogin->getLastName());
        $this->assertEquals($data['email'], $accountWithLogin->getEmail());
        $this->assertEquals($data['api_key'], $accountWithLogin->getApiKey());
        $this->assertEquals($data['reset_token'], $accountWithLogin->getResetToken());
        $this->assertEquals(new DateTime($data['reset_initiated_at']), $accountWithLogin->getResetInitiatedAt());
        $this->assertEquals(new DateTime($data['created_at']), $accountWithLogin->getCreatedAt());
        $this->assertEquals(new DateTime($data['updated_at']), $accountWithLogin->getUpdatedAt());
    }

    /**
     * Test the login method with invalid username.
     */
    public function testLogin_InvalidUsername() {
        $data = array(
            'username' => $this->generateRandomString(10),
            'password' => $this->generateRandomString(70),
            'seed' => $this->generateRandomString(20),
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
        $account->setPassword(hash('sha512', $data['seed'] . $data['password']));
        $account->setSeed($data['seed']);
        $account->setFirstName($data['first_name']);
        $account->setLastName($data['last_name']);
        $account->setEmail($data['email']);
        $account->setApiKey($data['api_key']);
        $account->setResetToken($data['reset_token']);
        $account->setResetInitiatedAt(new DateTime($data['reset_initiated_at']));
        $account->setCreatedAt(new DateTime($data['created_at']));
        $account->setUpdatedAt(new DateTime($data['updated_at']));

        $this->assertNull($account->getId());

        $account = $this->repo->create($account);

        $this->assertNotNull($account->getId());
        $this->assertEquals($data['username'], $account->getUsername());
        $this->assertEquals(hash('sha512', $data['seed'] . $data['password']), $account->getPassword());
        $this->assertEquals($data['seed'], $account->getSeed());
        $this->assertEquals($data['first_name'], $account->getFirstName());
        $this->assertEquals($data['last_name'], $account->getLastName());
        $this->assertEquals($data['email'], $account->getEmail());
        $this->assertEquals($data['api_key'], $account->getApiKey());
        $this->assertEquals($data['reset_token'], $account->getResetToken());
        $this->assertEquals(new DateTime($data['reset_initiated_at']), $account->getResetInitiatedAt());
        $this->assertEquals(new DateTime($data['created_at']), $account->getCreatedAt());
        $this->assertEquals(new DateTime($data['updated_at']), $account->getUpdatedAt());

        $accountWithLogin = $this->repo->findByUsernameAndPassword($data['username'] . 'random', $data['password'], $data['seed']);

        $this->assertNull($accountWithLogin);
    }

    /**
     * Test the login method with invalid password.
     */
    public function testLogin_InvalidPassword() {
        $data = array(
            'username' => $this->generateRandomString(10),
            'password' => $this->generateRandomString(70),
            'seed' => $this->generateRandomString(20),
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
        $account->setPassword(hash('sha512', $data['seed'] . $data['password']));
        $account->setSeed($data['seed']);
        $account->setFirstName($data['first_name']);
        $account->setLastName($data['last_name']);
        $account->setEmail($data['email']);
        $account->setApiKey($data['api_key']);
        $account->setResetToken($data['reset_token']);
        $account->setResetInitiatedAt(new DateTime($data['reset_initiated_at']));
        $account->setCreatedAt(new DateTime($data['created_at']));
        $account->setUpdatedAt(new DateTime($data['updated_at']));

        $this->assertNull($account->getId());

        $account = $this->repo->create($account);

        $this->assertNotNull($account->getId());
        $this->assertEquals($data['username'], $account->getUsername());
        $this->assertEquals(hash('sha512', $data['seed'] . $data['password']), $account->getPassword());
        $this->assertEquals($data['seed'], $account->getSeed());
        $this->assertEquals($data['first_name'], $account->getFirstName());
        $this->assertEquals($data['last_name'], $account->getLastName());
        $this->assertEquals($data['email'], $account->getEmail());
        $this->assertEquals($data['api_key'], $account->getApiKey());
        $this->assertEquals($data['reset_token'], $account->getResetToken());
        $this->assertEquals(new DateTime($data['reset_initiated_at']), $account->getResetInitiatedAt());
        $this->assertEquals(new DateTime($data['created_at']), $account->getCreatedAt());
        $this->assertEquals(new DateTime($data['updated_at']), $account->getUpdatedAt());

        $accountWithLogin = $this->repo->findByUsernameAndPassword($data['username'], $data['password'] . 'random', $data['seed']);

        $this->assertNull($accountWithLogin);
    }

    /**
     * Test the update one method, by creating a new valid entry, asserting it, updating that entry and then
     * asserting the update values.
     */
    public function testUpdateOne_Valid() {
        $createData = array(
            'username' => $this->generateRandomString(10),
            'password' => $this->generateRandomString(70),
            'seed' => $this->generateRandomString(20),
            'first_name' => $this->generateRandomString(60),
            'last_name' => $this->generateRandomString(60),
            'email' => $this->generateRandomString(120),
            'api_key' => $this->generateRandomString(128),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        $account = new Account();
        $account->setUsername($createData['username']);
        $account->setPassword(hash('sha512', $createData['seed'] . $createData['password']));
        $account->setSeed($createData['seed']);
        $account->setFirstName($createData['first_name']);
        $account->setLastName($createData['last_name']);
        $account->setEmail($createData['email']);
        $account->setApiKey($createData['api_key']);
        $account->setCreatedAt(new DateTime($createData['created_at']));
        $account->setUpdatedAt(new DateTime($createData['updated_at']));

        $this->assertNull($account->getId());

        $account = $this->repo->create($account);

        $this->assertNotNull($account->getId());
        $this->assertEquals($createData['username'], $account->getUsername());
        $this->assertEquals(hash('sha512', $createData['seed'] . $createData['password']), $account->getPassword());
        $this->assertEquals($createData['seed'], $account->getSeed());
        $this->assertEquals($createData['first_name'], $account->getFirstName());
        $this->assertEquals($createData['last_name'], $account->getLastName());
        $this->assertEquals($createData['email'], $account->getEmail());
        $this->assertEquals($createData['api_key'], $account->getApiKey());
        $this->assertEquals(new DateTime($createData['created_at']), $account->getCreatedAt());
        $this->assertEquals(new DateTime($createData['updated_at']), $account->getUpdatedAt());

        $updateData = array(
            'username' => $this->generateRandomString(10),
            'password' => $this->generateRandomString(70),
            'seed' => $this->generateRandomString(20),
            'first_name' => $this->generateRandomString(60),
            'last_name' => $this->generateRandomString(60),
            'email' => $this->generateRandomString(120),
            'api_key' => $this->generateRandomString(128),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        $accountUpdated = $this->repo->findOneBy(array('apiKey' => $createData['api_key']));

        $accountUpdated->setUsername($updateData['username']);
        $accountUpdated->setPassword(hash('sha512', $updateData['seed'] . $updateData['password']));
        $accountUpdated->setSeed($updateData['seed']);
        $accountUpdated->setFirstName($updateData['first_name']);
        $accountUpdated->setLastName($updateData['last_name']);
        $accountUpdated->setEmail($updateData['email']);
        $accountUpdated->setApiKey($updateData['api_key']);
        $accountUpdated->setCreatedAt(new DateTime($updateData['created_at']));
        $accountUpdated->setUpdatedAt(new DateTime($updateData['updated_at']));

        $accountUpdated = $this->repo->updateOne($accountUpdated);

        $this->assertNotNull($accountUpdated->getId());
        $this->assertEquals($updateData['username'], $accountUpdated->getUsername());
        $this->assertEquals(hash('sha512', $updateData['seed'] . $updateData['password']), $accountUpdated->getPassword());
        $this->assertEquals($updateData['seed'], $accountUpdated->getSeed());
        $this->assertEquals($updateData['first_name'], $accountUpdated->getFirstName());
        $this->assertEquals($updateData['last_name'], $accountUpdated->getLastName());
        $this->assertEquals($updateData['email'], $accountUpdated->getEmail());
        $this->assertEquals($updateData['api_key'], $accountUpdated->getApiKey());
        $this->assertEquals(new DateTime($updateData['created_at']), $accountUpdated->getCreatedAt());
        $this->assertEquals(new DateTime($updateData['updated_at']), $accountUpdated->getUpdatedAt());
    }
}