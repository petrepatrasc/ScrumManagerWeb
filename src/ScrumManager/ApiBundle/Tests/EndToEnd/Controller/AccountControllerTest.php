<?php

namespace ScrumManager\ApiBundle\Tests\EndToEnd;

class AccountControllerTest extends BaseFunctionalTestCase {

    /**
     * Test the account registration action by going through the test screen for it.
     */
    public function testRegister_Valid() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/testscreen/account/register');

        $form = $crawler->selectButton('Register')->form();

        $form['username'] = $this->generateRandomString(10);
        $form['password'] = $this->generateRandomString(10);
        $form['email'] = $this->generateRandomString(10) . '@dreamlabs.ro';
        $form['first_name'] = $this->generateRandomString(10);
        $form['last_name'] = $this->generateRandomString(10);

        $crawler = $client->submit($form);

        $this->assertSuccessfulResponse($client);
    }

    /**
     * Test the account registration action by sending an invalid email as a request.
     */
    public function testRegister_InvalidEmail() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/testscreen/account/register');

        $form = $crawler->selectButton('Register')->form();

        $form['username'] = $this->generateRandomString(10);
        $form['password'] = $this->generateRandomString(10);
        $form['email'] = $this->generateRandomString(10);
        $form['first_name'] = $this->generateRandomString(10);
        $form['last_name'] = $this->generateRandomString(10);

        $crawler = $client->submit($form);

        $this->assertErrorResponse($client);
    }

    /**
     * Test the account registration action by sending an invalid email as a request.
     */
    public function testRegister_InvalidUsername() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/testscreen/account/register');

        $form = $crawler->selectButton('Register')->form();

        $form['username'] = $this->generateRandomString(1);
        $form['password'] = $this->generateRandomString(10);
        $form['email'] = $this->generateRandomString(10) . '@dreamlabs.ro';
        $form['first_name'] = $this->generateRandomString(10);
        $form['last_name'] = $this->generateRandomString(10);

        $crawler = $client->submit($form);

        $this->assertErrorResponse($client);
    }

    /**
     * Test the authentication mechanism when dealing with valid data.
     */
    public function testLogin_ValidData() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/testscreen/account/register');

        $form = $crawler->selectButton('Register')->form();

        $username = $this->generateRandomString(10);
        $password = $this->generateRandomString(10);

        $form['username'] = $username;
        $form['password'] = $password;
        $form['email'] = $this->generateRandomString(10) . '@dreamlabs.ro';
        $form['first_name'] = $this->generateRandomString(10);
        $form['last_name'] = $this->generateRandomString(10);

        $crawler = $client->submit($form);

        $this->assertSuccessfulResponse($client);

        $crawler = $client->request('GET', '/api/testscreen/account/login');

        $form = $crawler->selectButton('Login')->form();

        $form['username'] = $username;
        $form['password'] = $password;

        $crawler = $client->submit($form);

        $this->assertSuccessfulResponse($client);
    }

    /**
     * Test the authentication mechanism when dealing with an invalid username.
     */
    public function testLogin_InvalidUsername() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/testscreen/account/register');

        $form = $crawler->selectButton('Register')->form();

        $username = $this->generateRandomString(10);
        $password = $this->generateRandomString(10);

        $form['username'] = $username;
        $form['password'] = $password;
        $form['email'] = $this->generateRandomString(10) . '@dreamlabs.ro';
        $form['first_name'] = $this->generateRandomString(10);
        $form['last_name'] = $this->generateRandomString(10);

        $crawler = $client->submit($form);

        $this->assertSuccessfulResponse($client);

        $crawler = $client->request('GET', '/api/testscreen/account/login');

        $form = $crawler->selectButton('Login')->form();

        $form['username'] = $username . "invalid";
        $form['password'] = $password;

        $crawler = $client->submit($form);

        $this->assertErrorResponse($client);
    }

    /**
     * Test the authentication mechanism when dealing with an invalid password.
     */
    public function testLogin_InvalidPassword() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/testscreen/account/register');

        $form = $crawler->selectButton('Register')->form();

        $username = $this->generateRandomString(10);
        $password = $this->generateRandomString(10);

        $form['username'] = $username;
        $form['password'] = $password;
        $form['email'] = $this->generateRandomString(10) . '@dreamlabs.ro';
        $form['first_name'] = $this->generateRandomString(10);
        $form['last_name'] = $this->generateRandomString(10);

        $crawler = $client->submit($form);

        $this->assertSuccessfulResponse($client);

        $crawler = $client->request('GET', '/api/testscreen/account/login');

        $form = $crawler->selectButton('Login')->form();

        $form['username'] = $username;
        $form['password'] = $password . 'invalid';

        $crawler = $client->submit($form);

        $this->assertErrorResponse($client);
    }

    /**
     * Test the updating mechanism for a single entry.
     */
    public function testUpdateOne_Valid() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/testscreen/account/register');

        $form = $crawler->selectButton('Register')->form();

        $username = $this->generateRandomString(10);
        $password = $this->generateRandomString(10);

        $form['username'] = $username;
        $form['password'] = $password;
        $form['email'] = $this->generateRandomString(10) . '@dreamlabs.ro';
        $form['first_name'] = $this->generateRandomString(10);
        $form['last_name'] = $this->generateRandomString(10);

        $crawler = $client->submit($form);

        $this->assertSuccessfulResponse($client);

        $crawler = $client->request('GET', '/api/testscreen/account/login');
        $form = $crawler->selectButton('Login')->form();
        $form['username'] = $username;
        $form['password'] = $password;

        $crawler = $client->submit($form);
        $responseData = $this->assertSuccessfulResponse($client);

        $apiKey = $responseData['api_key'];
        $crawler = $client->request('GET', '/api/testscreen/account/updateOne');

        $form = $crawler->selectButton('Update')->form();

        $username = $this->generateRandomString(10);
        $password = $this->generateRandomString(10);

        $form['username'] = $username;
        $form['password'] = $password;
        $form['email'] = $this->generateRandomString(10) . '@dreamlabs.ro';
        $form['first_name'] = $this->generateRandomString(10);
        $form['last_name'] = $this->generateRandomString(10);
        $form['api_key'] = $apiKey;

        $crawler = $client->submit($form);

        $this->assertSuccessfulResponse($client);
    }

    /**
     * Test the updating mechanism for a single entry, but with an invalid API key.
     */
    public function testUpdateOne_InvalidApiKey() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/testscreen/account/register');

        $form = $crawler->selectButton('Register')->form();

        $username = $this->generateRandomString(10);
        $password = $this->generateRandomString(10);

        $form['username'] = $username;
        $form['password'] = $password;
        $form['email'] = $this->generateRandomString(10) . '@dreamlabs.ro';
        $form['first_name'] = $this->generateRandomString(10);
        $form['last_name'] = $this->generateRandomString(10);

        $crawler = $client->submit($form);

        $this->assertSuccessfulResponse($client);

        $crawler = $client->request('GET', '/api/testscreen/account/login');
        $form = $crawler->selectButton('Login')->form();
        $form['username'] = $username;
        $form['password'] = $password;

        $crawler = $client->submit($form);
        $responseData = $this->assertSuccessfulResponse($client);

        $apiKey = $this->generateRandomString(20);
        $crawler = $client->request('GET', '/api/testscreen/account/updateOne');

        $form = $crawler->selectButton('Update')->form();

        $username = $this->generateRandomString(10);
        $password = $this->generateRandomString(10);

        $form['username'] = $username;
        $form['password'] = $password;
        $form['email'] = $this->generateRandomString(10) . '@dreamlabs.ro';
        $form['first_name'] = $this->generateRandomString(10);
        $form['last_name'] = $this->generateRandomString(10);
        $form['api_key'] = $apiKey;

        $crawler = $client->submit($form);

        $this->assertErrorResponse($client);
    }

    /*
     * Test the change password mechanism when providing valid data.
     */
    public function testChangePassword_Valid() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/testscreen/account/register');

        $form = $crawler->selectButton('Register')->form();

        $username = $this->generateRandomString(10);
        $password = $this->generateRandomString(10);

        $form['username'] = $username;
        $form['password'] = $password;
        $form['email'] = $this->generateRandomString(10) . '@dreamlabs.ro';
        $form['first_name'] = $this->generateRandomString(10);
        $form['last_name'] = $this->generateRandomString(10);

        $crawler = $client->submit($form);

        $this->assertSuccessfulResponse($client);

        $crawler = $client->request('GET', '/api/testscreen/account/login');
        $form = $crawler->selectButton('Login')->form();
        $form['username'] = $username;
        $form['password'] = $password;

        $crawler = $client->submit($form);
        $responseData = $this->assertSuccessfulResponse($client);

        $apiKey = $responseData['api_key'];
        $crawler = $client->request('GET', '/api/testscreen/account/changePassword');

        $form = $crawler->selectButton('Change Password')->form();

        $oldPassword = $password;
        $newPassword = $this->generateRandomString(60);

        $form['old_password'] = $oldPassword;
        $form['new_password'] = $newPassword;
        $form['api_key'] = $apiKey;

        $crawler = $client->submit($form);
        $this->assertSuccessfulResponse($client);
    }

    /*
     * Test the change password mechanism when providing invalid old password.
     */
    public function testChangePassword_InvalidOldPassword() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/testscreen/account/register');

        $form = $crawler->selectButton('Register')->form();

        $username = $this->generateRandomString(10);
        $password = $this->generateRandomString(10);

        $form['username'] = $username;
        $form['password'] = $password;
        $form['email'] = $this->generateRandomString(10) . '@dreamlabs.ro';
        $form['first_name'] = $this->generateRandomString(10);
        $form['last_name'] = $this->generateRandomString(10);

        $crawler = $client->submit($form);

        $this->assertSuccessfulResponse($client);

        $crawler = $client->request('GET', '/api/testscreen/account/login');
        $form = $crawler->selectButton('Login')->form();
        $form['username'] = $username;
        $form['password'] = $password;

        $crawler = $client->submit($form);
        $responseData = $this->assertSuccessfulResponse($client);

        $apiKey = $responseData['api_key'];
        $crawler = $client->request('GET', '/api/testscreen/account/changePassword');

        $form = $crawler->selectButton('Change Password')->form();

        $oldPassword = $password . 'invalid';
        $newPassword = $this->generateRandomString(60);

        $form['old_password'] = $oldPassword;
        $form['new_password'] = $newPassword;
        $form['api_key'] = $apiKey;

        $crawler = $client->submit($form);
        $this->assertErrorResponse($client);
    }

    /*
     * Test the change password mechanism when providing invalid API key.
     */
    public function testChangePassword_InvalidApiKey() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/testscreen/account/register');

        $form = $crawler->selectButton('Register')->form();

        $username = $this->generateRandomString(10);
        $password = $this->generateRandomString(10);

        $form['username'] = $username;
        $form['password'] = $password;
        $form['email'] = $this->generateRandomString(10) . '@dreamlabs.ro';
        $form['first_name'] = $this->generateRandomString(10);
        $form['last_name'] = $this->generateRandomString(10);

        $crawler = $client->submit($form);

        $this->assertSuccessfulResponse($client);

        $crawler = $client->request('GET', '/api/testscreen/account/login');
        $form = $crawler->selectButton('Login')->form();
        $form['username'] = $username;
        $form['password'] = $password;

        $crawler = $client->submit($form);
        $responseData = $this->assertSuccessfulResponse($client);

        $apiKey = $this->generateRandomString(20);
        $crawler = $client->request('GET', '/api/testscreen/account/changePassword');

        $form = $crawler->selectButton('Change Password')->form();

        $oldPassword = $password;
        $newPassword = $this->generateRandomString(60);

        $form['old_password'] = $oldPassword;
        $form['new_password'] = $newPassword;
        $form['api_key'] = $apiKey;

        $crawler = $client->submit($form);
        $this->assertErrorResponse($client);
    }
}