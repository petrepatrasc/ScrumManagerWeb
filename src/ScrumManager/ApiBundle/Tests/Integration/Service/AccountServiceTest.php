<?php

namespace ScrumManager\ApiBundle\Tests\Integration;


use MyProject\Proxies\__CG__\OtherProject\Proxies\__CG__\stdClass;
use ScrumManager\ApiBundle\Entity\Account;
use ScrumManager\ApiBundle\Repository\AccountRepository;
use ScrumManager\ApiBundle\Service\AccountService;
use ScrumManager\ApiBundle\Service\GeneralHelperService;
use Symfony\Component\Validator\Validator;
use \DateTime;

class AccountServiceTest extends BaseIntegrationTestCase {

    /**
     * @var AccountRepository
     */
    protected $repo;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @var array
     */
    protected $seedData;

    public function setUp() {
        parent::setUp();
        $this->repo = $this->em->getRepository('ScrumManagerApiBundle:Account');
        $this->validator = static::$kernel->getContainer()->get('validator');

        $this->seedData = array(
            'username' => GeneralHelperService::generateRandomString(10),
            'password' => GeneralHelperService::generateRandomString(128),
            'firstName' => GeneralHelperService::generateRandomString(60),
            'lastName' => GeneralHelperService::generateRandomString(60),
            'email' => GeneralHelperService::generateRandomString(108) . '@dreamlabs.ro',
            'apiKey' => GeneralHelperService::generateRandomString(128),
            'resetToken' => null,
            'resetInitiatedAt' => null,
            'createdAt' => new DateTime('now'),
            'updatedAt' => new DateTime('now')
        );
    }

    public function tearDown() {
        parent::tearDown();
        unset($this->repo);
        unset($this->validator);
        unset($this->seedData);
    }

    /**
     * Test the registration method, with valid parameters, and make sure that we receive an entity
     * after going through all of the
     */
    public function testRegister_Valid() {
        $accountService = new AccountService($this->validator, $this->em);

        $account = $accountService->register($this->seedData);

        $this->assertNotNull($account['id']);
        $this->assertEquals($this->seedData['username'], $account['username']);
        $this->assertNotEquals($this->seedData['password'], $account['password']);
        $this->assertEquals($this->seedData['firstName'], $account['firstName']);
        $this->assertEquals($this->seedData['lastName'], $account['lastName']);
        $this->assertEquals($this->seedData['email'], $account['email']);
        $this->assertNull($account['resetToken']);
        $this->assertNull($account['resetInitiatedAt']);
    }

    /**
     * Test that a single unique username can be registered into the system.
     */
    public function testRegister_UsernameUnique() {
        $accountService = new AccountService($this->validator, $this->em);

        $firstAccount = $accountService->register($this->seedData);
        $secondAccount = $accountService->register($this->seedData);

        $this->assertNotNull($firstAccount['id']);
        $this->assertEquals($this->seedData['username'], $firstAccount['username']);
        $this->assertNotEquals($this->seedData['password'], $firstAccount['password']);
        $this->assertEquals($this->seedData['firstName'], $firstAccount['firstName']);
        $this->assertEquals($this->seedData['lastName'], $firstAccount['lastName']);
        $this->assertEquals($this->seedData['email'], $firstAccount['email']);
        $this->assertNull($firstAccount['resetToken']);
        $this->assertNull($firstAccount['resetInitiatedAt']);

        $this->assertNull($secondAccount);
    }

    /**
     * Test the register action when the username is null.
     */
    public function testRegister_UsernameNull() {
        $this->seedData['username'] = null;

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the username is blank.
     */
    public function testRegister_UsernameBlank() {
        $this->seedData['username'] = '';

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the username is integer.
     */
    public function testRegister_UsernameIsInteger() {
        $this->seedData['username'] = 1;

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the username is too small.
     */
    public function testRegister_UsernameIsTooSmall() {
        $this->seedData['username'] = 'x';

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the username is too long..
     */
    public function testRegister_UsernameIsTooLarge() {
        $this->seedData['username'] = GeneralHelperService::generateRandomString(81);

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the username is unset..
     */
    public function testRegister_UsernameUnset() {
        unset($this->seedData['username']);

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the password is not set.
     */
    public function testRegister_PasswordIsNotSet() {
        unset($this->seedData['password']);

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNotNull($account['id']);
        $this->assertEquals($this->seedData['username'], $account['username']);
        $this->assertNotNull($account['password']);
        $this->assertNotEquals($account['password'], hash('sha512', $account['seed']));
        $this->assertEquals($this->seedData['firstName'], $account['firstName']);
        $this->assertEquals($this->seedData['lastName'], $account['lastName']);
        $this->assertEquals($this->seedData['email'], $account['email']);
        $this->assertNull($account['resetToken']);
        $this->assertNull($account['resetInitiatedAt']);
    }

    /**
     * Test the register action when the email is not in a valid format.
     */
    public function testRegister_EmailIsNotValid() {
        $this->seedData['email'] = 'invalid.com';

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the email is not in unset.
     */
    public function testRegister_EmailIsUnset() {
        unset($this->seedData['email']);

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the email is blank.
     */
    public function testRegister_EmailIsBlank() {
        $this->seedData['email'] = '';

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the email is null.
     */
    public function testRegister_EmailIsNull() {
        $this->seedData['email'] = null;

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the email is an integer.
     */
    public function testRegister_EmailIsInteger() {
        $this->seedData['email'] = 3;

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the email is too short.
     */
    public function testRegister_EmailIsTooShort() {
        $this->seedData['email'] = 't@a.com';

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the email is too long.
     */
    public function testRegister_EmailIsTooLong() {
        $this->seedData['email'] = GeneralHelperService::generateRandomString(168) . '@dreamlabs.ro';

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the first name is blank.
     */
    public function testRegister_FirstNameIsBlank() {
        $this->seedData['firstName'] = '';

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the first name is null.
     */
    public function testRegister_FirstNameIsNull() {
        $this->seedData['firstName'] = null;

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the first name is not of a valid type.
     */
    public function testRegister_FirstNameIsInteger() {
        $this->seedData['firstName'] = 100;

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the first name is unset.
     */
    public function testRegister_FirstNameIsUnset() {
        unset($this->seedData['firstName']);

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the first name is too long.
     */
    public function testRegister_FirstNameIsTooLong() {
        $this->seedData['firstName'] = GeneralHelperService::generateRandomString(81);

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the last name is blank.
     */
    public function testRegister_LastNameIsBlank() {
        $this->seedData['lastName'] = '';

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the last name is null.
     */
    public function testRegister_LastNameIsNull() {
        $this->seedData['lastName'] = null;

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the last name is not of a valid type.
     */
    public function testRegister_LastNameIsInteger() {
        $this->seedData['lastName'] = 100;

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the last name is unset.
     */
    public function testRegister_LastNameIsUnset() {
        unset($this->seedData['lastName']);

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the last name is too long.
     */
    public function testRegister_LastNameIsTooLong() {
        $this->seedData['lastName'] = GeneralHelperService::generateRandomString(81);

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNull($account);
    }

    /**
     * Test the register action when the createdAt field is missing.
     */
    public function testRegister_CreatedAtIsNotSet() {
        unset($this->seedData['createdAt']);

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNotNull($account['id']);
        $this->assertEquals($this->seedData['username'], $account['username']);
        $this->assertNotEquals($this->seedData['password'], $account['password']);
        $this->assertEquals($this->seedData['firstName'], $account['firstName']);
        $this->assertEquals($this->seedData['lastName'], $account['lastName']);
        $this->assertEquals($this->seedData['email'], $account['email']);
        $this->assertNull($account['resetToken']);
        $this->assertNull($account['resetInitiatedAt']);
        $this->assertNotNull($account['createdAt']);
    }

    /**
     * Test the register action when the updatedAt field is missing.
     */
    public function testRegister_UpdatedAtIsNotSet() {
        unset($this->seedData['updatedAt']);

        $accountService = new AccountService($this->validator, $this->em);
        $account = $accountService->register($this->seedData);

        $this->assertNotNull($account['id']);
        $this->assertEquals($this->seedData['username'], $account['username']);
        $this->assertNotEquals($this->seedData['password'], $account['password']);
        $this->assertEquals($this->seedData['firstName'], $account['firstName']);
        $this->assertEquals($this->seedData['lastName'], $account['lastName']);
        $this->assertEquals($this->seedData['email'], $account['email']);
        $this->assertNull($account['resetToken']);
        $this->assertNull($account['resetInitiatedAt']);
        $this->assertNotNull($account['updatedAt']);
    }

    /**
     * Register a new account and make sure that its data is valid.
     * @param AccountService $service The service that should be called.
     * @return Account Account entity that was created.
     */
    protected function createNewAccountAndAssertIt(AccountService $service) {
        $account = $service->register($this->seedData);

        $this->assertNotNull($account['id']);
        $this->assertEquals($this->seedData['username'], $account['username']);
        $this->assertNotEquals($this->seedData['password'], $account['password']);
        $this->assertEquals($this->seedData['firstName'], $account['firstName']);
        $this->assertEquals($this->seedData['lastName'], $account['lastName']);
        $this->assertEquals($this->seedData['email'], $account['email']);
        $this->assertNull($account['resetToken']);
        $this->assertNull($account['resetInitiatedAt']);

        return $account;
    }

    /**
     * Test the login method - first we will create a new user, and then attempt to log in using their credentials.
     */
    public function testLogin_Valid() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $accountLogin = $accountService->login($this->seedData['username'], $this->seedData['password']);

        $this->assertNotNull($accountLogin);
        $this->assertNotNull($accountLogin['id']);
        $this->assertEquals($this->seedData['username'], $accountLogin['username']);
        $this->assertNotEquals($this->seedData['password'], $accountLogin['password']);
        $this->assertEquals($this->seedData['firstName'], $accountLogin['firstName']);
        $this->assertEquals($this->seedData['lastName'], $accountLogin['lastName']);
        $this->assertEquals($this->seedData['email'], $accountLogin['email']);
        $this->assertNull($accountLogin['resetToken']);
        $this->assertNull($accountLogin['resetInitiatedAt']);
    }

    /**
     * Test the login method when the username is invalid. We should retrieve a null result set in this scenario.
     */
    public function testLogin_InvalidUsername() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $accountLogin = $accountService->login($this->seedData['username'] . 'invalid', $this->seedData['password']);

        $this->assertNull($accountLogin);
    }

    /**
     * Test the login method when the password is invalid. We should retrieve a null result set in this scenario.
     */
    public function testLogin_InvalidPassword() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $accountLogin = $accountService->login($this->seedData['username'], $this->seedData['password'] . 'invalid');

        $this->assertNull($accountLogin);
    }

    /**
     * Test the login method when the account has been deactivated. We should retrieve a null result set in this scenario.
     */
    public function testLogin_InvalidAccountDeactivated() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $account = $accountService->deactivateAccount($account['apiKey']);

        $accountLogin = $accountService->login($this->seedData['username'], $this->seedData['password']);

        $this->assertNull($accountLogin);
    }

    /**
     * Test the update method, by first creating a valid user, asserting its data and then updating that data
     * via the service, and asserting the results.
     */
    public function testUpdateOne_Valid() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $updateData = array(
            'username' => GeneralHelperService::generateRandomString(10),
            'password' => GeneralHelperService::generateRandomString(128),
            'firstName' => GeneralHelperService::generateRandomString(60),
            'lastName' => GeneralHelperService::generateRandomString(60),
            'email' => GeneralHelperService::generateRandomString(108) . '@dreamlabs.ro',
            'resetToken' => null,
            'resetInitiatedAt' => null,
            'createdAt' => new DateTime('now'),
            'updatedAt' => new DateTime('now')
        );

        $account = $accountService->updateOne($account['apiKey'], $updateData);

        $this->assertNotNull($account);
        $this->assertNotNull($account['id']);
        $this->assertEquals($updateData['username'], $account['username']);
        $this->assertNotEquals(hash('sha512', $account['seed'] . $updateData['password']), $account['password']);
        $this->assertEquals($updateData['firstName'], $account['firstName']);
        $this->assertEquals($updateData['lastName'], $account['lastName']);
        $this->assertEquals($updateData['email'], $account['email']);
        $this->assertNull($account['resetToken']);
        $this->assertNull($account['resetInitiatedAt']);
    }

    /**
     * Test the update method, by providing an account that has been deactivated.
     */
    public function testUpdateOne_InvalidAccountDeactivated() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $updateData = array(
            'username' => GeneralHelperService::generateRandomString(10),
            'password' => GeneralHelperService::generateRandomString(128),
            'firstName' => GeneralHelperService::generateRandomString(60),
            'lastName' => GeneralHelperService::generateRandomString(60),
            'email' => GeneralHelperService::generateRandomString(108) . '@dreamlabs.ro',
            'resetToken' => null,
            'resetInitiatedAt' => null,
            'createdAt' => new DateTime('now'),
            'updatedAt' => new DateTime('now')
        );

        $account = $accountService->deactivateAccount($account['apiKey']);
        $account = $accountService->updateOne($account['apiKey'], $updateData);

        $this->assertNull($account);
    }

    /**
     * Test the update method, by providing an invalid API key.
     */
    public function testUpdateOnce_InvalidApiKey() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $updateData = array(
            'username' => GeneralHelperService::generateRandomString(10),
            'password' => GeneralHelperService::generateRandomString(128),
            'firstName' => GeneralHelperService::generateRandomString(60),
            'lastName' => GeneralHelperService::generateRandomString(60),
            'email' => GeneralHelperService::generateRandomString(108) . '@dreamlabs.ro',
            'resetToken' => null,
            'resetInitiatedAt' => null,
            'createdAt' => new DateTime('now'),
            'updatedAt' => new DateTime('now')
        );

        $account = $accountService->updateOne(GeneralHelperService::generateRandomString(20), $updateData);

        $this->assertNull($account);
    }

    /**
     * Test the change password mechanism with valid data.
     */
    public function testChangePassword_Valid() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $newPass = GeneralHelperService::generateRandomString(100);
        $account = $accountService->changePassword($account['apiKey'], $this->seedData['password'], $newPass);

        $this->assertNotNull($account);
        $this->assertNotEquals($account['password'], hash('sha512', $account['seed'] . $this->seedData['password']));
        $this->assertEquals($account['password'], hash('sha512', $account['seed'] . $newPass));
    }

    /**
     * Test the change password mechanism when providing an invalid API key.
     */
    public function testChangePassword_InvalidApiKey() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $newPass = GeneralHelperService::generateRandomString(100);
        $account = $accountService->changePassword($account['apiKey'] . 'invalid', $this->seedData['password'], $newPass);

        $this->assertNull($account);
    }

    /**
     * Test the change password mechanism when providing the account has been disabled.
     */
    public function testChangePassword_InvalidAccountDeactivated() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $account = $accountService->deactivateAccount($account['apiKey']);

        $newPass = GeneralHelperService::generateRandomString(100);
        $account = $accountService->changePassword($account['apiKey'], $this->seedData['password'], $newPass);

        $this->assertNull($account);
    }

    /**
     * Test the change password mechanism when providing an invalid old password.
     */
    public function testChangePassword_InvalidOldPassword() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $newPass = GeneralHelperService::generateRandomString(100);
        $account = $accountService->changePassword($account['apiKey'], $this->seedData['password'] . 'invalid', $newPass);

        $this->assertNull($account);
    }

    /**
     * Test the mechanism for retrieving a single account when data is valid.
     */
    public function testRetrieveOne_Valid() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $retrievedAccount = $accountService->retrieveOne($account['username']);

        $this->assertNotNull($retrievedAccount);
        $this->assertNotNull($retrievedAccount['id']);
        $this->assertEquals($this->seedData['username'], $retrievedAccount['username']);
        $this->assertEquals($this->seedData['firstName'], $retrievedAccount['firstName']);
        $this->assertEquals($this->seedData['lastName'], $retrievedAccount['lastName']);
        $this->assertEquals($this->seedData['email'], $retrievedAccount['email']);
        $this->assertNull($retrievedAccount['resetToken']);
        $this->assertNull($retrievedAccount['resetInitiatedAt']);
    }

    /**
     * Test the mechanism for retriving a single account when the username does not exist.
     */
    public function testRetrieveOne_InvalidUsername() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $retrievedAccount = $accountService->retrieveOne($account['username'] . 'invalid');

        $this->assertNull($retrievedAccount);
    }

    /**
     * Test the mechanism for retriving a single account when the account has already been deactivated.
     */
    public function testRetrieveOne_InvalidAccountDeactivated() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $account = $accountService->deactivateAccount($account['apiKey']);

        $retrievedAccount = $accountService->retrieveOne($account['username']);

        $this->assertNull($retrievedAccount);
    }

    /**
     * Test the deactivation mechanism for a single account and when the data is valid.
     */
    public function testDeactivateAccount_Valid() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $retrievedAccount = $accountService->deactivateAccount($account['apiKey']);

        $this->assertNotNull($retrievedAccount);
        $this->assertFalse($retrievedAccount['active']);
    }

    /**
     * Test the deactivation mechanism for a single account and when the API key is invalid.
     */
    public function testDeactivateAccount_InvalidApiKey() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $retrievedAccount = $accountService->deactivateAccount($account['apiKey'] . 'invalid');

        $this->assertNull($retrievedAccount);
    }

    /**
     * Test the deactivation mechanism for a single account and when the account has already been disabled.
     */
    public function testDeactivateAccount_InvalidAccountAlreadyDisabled() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $retrievedAccount = $accountService->deactivateAccount($account['apiKey']);

        $this->assertNotNull($retrievedAccount);
        $this->assertFalse($retrievedAccount['active']);

        $retrievedAccount = $accountService->deactivateAccount($account['apiKey']);
        $this->assertNull($retrievedAccount);
    }

    /**
     * Test the password reset mechanism when the data is valid.
     */
    public function testResetPasswordForAccount_Valid() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $account = $accountService->resetPassword($account['apiKey']);

        $this->assertNotNull($account);
        $this->assertNotNull($account['resetToken']);
        $this->assertNotNull($account['resetInitiatedAt']);
    }

    /**
     * Test the password reset mechanism when the API key is invalid.
     */
    public function testResetPasswordForAccount_InvalidApiKey() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $account = $accountService->resetPassword($account['apiKey'] . 'invalid');

        $this->assertNull($account);
    }

    /**
     * Test the password reset mechanism when the account has already been previously deactivated.
     */
    public function testResetPasswordForAccount_InvalidAccountDeactivated() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $account = $accountService->deactivateAccount($account['apiKey']);
        $account = $accountService->resetPassword($account['apiKey']);

        $this->assertNull($account);
    }

    /**
     * Test adding a new password when the data is valid, in the happy flow.
     */
    public function testNewPassword_Valid() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $account = $accountService->resetPassword($account['apiKey']);
        $this->assertNotNull($account);

        $oldPassword = $account['password'];
        $newPassword = GeneralHelperService::generateRandomString(10);
        $account = $accountService->newPassword($account['apiKey'], $account['resetToken'], $newPassword);

        $this->assertNotNull($account);
        $this->assertNull($account['resetInitiatedAt']);
        $this->assertNull($account['resetToken']);
        $this->assertNotEquals($oldPassword, $account['password']);
    }

    /**
     * Test adding a new password when the API key is invalid.
     */
    public function testNewPassword_InvalidApiKey() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $account = $accountService->resetPassword($account['apiKey']);
        $this->assertNotNull($account);

        $newPassword = GeneralHelperService::generateRandomString(10);
        $account = $accountService->newPassword($account['apiKey'] . 'invalid', $account['resetToken'], $newPassword);

        $this->assertNull($account);
    }

    /**
     * Test adding a new password when the reset token is invalid.
     */
    public function testNewPassword_InvalidResetToken() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $account = $accountService->resetPassword($account['apiKey']);
        $this->assertNotNull($account);

        $newPassword = GeneralHelperService::generateRandomString(10);
        $account = $accountService->newPassword($account['apiKey'], $account['resetToken'] . 'invalid', $newPassword);

        $this->assertNull($account);
    }

    /**
     * Test adding a new password when the account is deactivated.
     */
    public function testNewPassword_InvalidAccountDeactivated() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $account = $accountService->resetPassword($account['apiKey']);
        $this->assertNotNull($account);

        $account = $accountService->deactivateAccount($account['apiKey']);
        $newPassword = GeneralHelperService::generateRandomString(10);
        $account = $accountService->newPassword($account['apiKey'], $account['resetToken'], $newPassword);

        $this->assertNull($account);
    }

    /**
     * Test adding a new password when the request for a reset was never made.
     */
    public function testNewPassword_InvalidResetRequestNeverMade() {
        $accountService = new AccountService($this->validator, $this->em);
        $account = $this->createNewAccountAndAssertIt($accountService);

        $newPassword = GeneralHelperService::generateRandomString(10);
        $account = $accountService->newPassword($account['apiKey'], $account['resetToken'], $newPassword);

        $this->assertNull($account);
    }
}