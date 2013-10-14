<?php

namespace ScrumManager\ApiBundle\Tests\Integration;


use ScrumManager\ApiBundle\Entity\Account;
use ScrumManager\ApiBundle\Repository\AccountRepository;
use \DateTime;
use ScrumManager\ApiBundle\Service\GeneralHelperService;

class AccountRepositoryTest extends BaseIntegrationTestCase {

    /**
     * @var AccountRepository
     */
    protected $repo;

    protected $seedData;

    public function setUp() {
        parent::setUp();
        $this->repo = $this->em->getRepository('ScrumManagerApiBundle:Account');
        $this->seedData = $this->generateRandomSeedData();
    }

    public function tearDown() {
        parent::tearDown();
        unset($this->repo);
    }

    protected function generateRandomSeedData() {
        return array(
            'username' => GeneralHelperService::generateRandomString(10),
            'password' => GeneralHelperService::generateRandomString(128),
            'seed' => GeneralHelperService::generateRandomString(20),
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
     * Create a new entity from the keys of a normalised array.
     * @param array $data The array that should be used for creating the entity.
     * @return Account The entity which is generated.
     */
    protected function createNewEntityFromArray($data) {
        $account = $this->serializer->denormalize($data, 'ScrumManager\ApiBundle\Entity\Account');
        $account->setPassword(hash('sha512', $data['seed'] . $data['password']));
        return $account;
    }

    public function assertAllValuesBetweenArrayAndEntity($data, Account $account) {
        $this->assertEquals($data['username'], $account->getUsername());
        $this->assertEquals(hash('sha512', $data['seed'] . $data['password']), $account->getPassword());
        $this->assertEquals($data['seed'], $account->getSeed());
        $this->assertEquals($data['firstName'], $account->getFirstName());
        $this->assertEquals($data['lastName'], $account->getLastName());
        $this->assertEquals($data['email'], $account->getEmail());
        $this->assertEquals($data['apiKey'], $account->getApiKey());
        $this->assertEquals($data['resetToken'], $account->getResetToken());
        $this->assertEquals($data['resetInitiatedAt'], $account->getResetInitiatedAt());
//        $this->assertEquals($data['createdAt'], $account->getCreatedAt());
//        $this->assertEquals($data['updatedAt'], $account->getUpdatedAt());
    }

    /**
     * Test the create method with valid data.
     */
    public function testCreate_Valid() {
        $account = $this->createNewEntityFromArray($this->seedData);
        $this->assertNull($account->getId());
        $account = $this->repo->create($account);

        $this->assertNotNull($account->getId());
        $this->assertAllValuesBetweenArrayAndEntity($this->seedData, $account);
    }

    /**
     * Test the login method with valid data.
     */
    public function testLogin_Valid() {
        $account = $this->createNewEntityFromArray($this->seedData);
        $this->assertNull($account->getId());

        $account = $this->repo->create($account);

        $this->assertNotNull($account->getId());
        $this->assertAllValuesBetweenArrayAndEntity($this->seedData, $account);

        $accountWithLogin = $this->repo->findByUsernameAndPassword($this->seedData['username'], $this->seedData['password'], $this->seedData['seed']);
        $this->assertAllValuesBetweenArrayAndEntity($this->seedData, $accountWithLogin);
    }

    /**
     * Test the login method with invalid username.
     */
    public function testLogin_InvalidUsername() {
        $account = $this->createNewEntityFromArray($this->seedData);
        $this->assertNull($account->getId());

        $account = $this->repo->create($account);

        $this->assertNotNull($account->getId());
        $this->assertAllValuesBetweenArrayAndEntity($this->seedData, $account);

        $accountWithLogin = $this->repo->findByUsernameAndPassword($this->seedData['username'] . GeneralHelperService::generateRandomString(10), $this->seedData['password'], $this->seedData['seed']);
        $this->assertNull($accountWithLogin);
    }

    /**
     * Test the login method with invalid password.
     */
    public function testLogin_InvalidPassword() {
        $account = $this->createNewEntityFromArray($this->seedData);
        $this->assertNull($account->getId());

        $account = $this->repo->create($account);

        $this->assertNotNull($account->getId());
        $this->assertAllValuesBetweenArrayAndEntity($this->seedData, $account);

        $accountWithLogin = $this->repo->findByUsernameAndPassword($this->seedData['username'], $this->seedData['password'] . GeneralHelperService::generateRandomString(10), $this->seedData['seed']);
        $this->assertNull($accountWithLogin);
    }

    /**
     * Test the update one method, by creating a new valid entry, asserting it, updating that entry and then
     * asserting the update values.
     */
    public function testUpdateOne_Valid() {
        $account = $this->createNewEntityFromArray($this->seedData);
        $this->assertNull($account->getId());

        $account = $this->repo->create($account);

        $this->assertNotNull($account->getId());
        $this->assertAllValuesBetweenArrayAndEntity($this->seedData, $account);

        $updateData = $this->generateRandomSeedData();
        $accountUpdated = $this->repo->findOneBy(array('apiKey' => $this->seedData['apiKey']));
        $accountUpdatedArray = $this->serializer->normalize($accountUpdated);
        $accountUpdatedArray['resetInitiatedAt'] = new DateTime(strtotime($accountUpdatedArray['resetInitiatedAt']['timestamp']));
        $accountUpdatedArray['createdAt'] = new DateTime(strtotime($accountUpdatedArray['createdAt']['timestamp']));
        $accountUpdatedArray['updatedAt'] = new DateTime(strtotime($accountUpdatedArray['updatedAt']['timestamp']));
        $accountUpdatedArray = array_merge($accountUpdatedArray, $updateData);

        $accountUpdated = $this->createNewEntityFromArray($accountUpdatedArray);
        $accountUpdated = $this->repo->updateOne($accountUpdated);

        $this->assertNotNull($accountUpdated->getId());
        $this->assertAllValuesBetweenArrayAndEntity($updateData, $accountUpdated);
    }
}