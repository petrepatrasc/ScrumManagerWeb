<?php

namespace ScrumManager\ApiBundle\Tests\EndToEnd;


use ScrumManager\ApiBundle\ResponseCode\Email\ResponseEmailCreateFailure;

class EmailControllerTest extends BaseFunctionalTestCase {

    /**
     * Test the functionality for a new email sent out from the system, when request data is valid.
     */
    public function testCrateNewFromSystem_Valid() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/testscreen/Email/CreateNewFromSystem');

        $form = $crawler->selectButton('Send email')->form();

        $form['receiver'] = $this->generateRandomString(20);
        $form['subject'] = $this->generateRandomString(80);
        $form['content'] = $this->generateRandomString(600);

        $crawler = $client->submit($form);

        $this->assertSuccessfulResponse($client);
    }

    /**
     * Test the functionality for a new email sent out from the system, when request data is invalid.
     */
    public function testCrateNewFromSystem_ReceiverTooLong() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/testscreen/Email/CreateNewFromSystem');

        $form = $crawler->selectButton('Send email')->form();

        $form['receiver'] = $this->generateRandomString(81);
        $form['subject'] = $this->generateRandomString(80);
        $form['content'] = $this->generateRandomString(600);

        $crawler = $client->submit($form);

        $responseData = $this->assertErrorResponse($client);
        $this->assertEquals(ResponseEmailCreateFailure::$code, $responseData['status']);
    }
}