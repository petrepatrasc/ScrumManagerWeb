<?php

namespace ScrumManager\ApiBundle\Tests\Integration;


class AccountControllerTest extends BaseControllerTestCase {

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
}