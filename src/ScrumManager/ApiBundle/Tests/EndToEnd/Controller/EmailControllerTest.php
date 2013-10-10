<?php

namespace ScrumManager\ApiBundle\Tests\EndToEnd;


use ScrumManager\ApiBundle\ResponseCode\Email\ResponseEmailCreateFailure;
use ScrumManager\ApiBundle\ResponseCode\Email\ResponseEmailReadFailure;
use ScrumManager\ApiBundle\ResponseCode\Email\ResponseEmailRetrieveFailure;

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


    public function testRetrieveOne_Success() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/testscreen/Email/CreateNewFromSystem');

        $form = $crawler->selectButton('Send email')->form();

        $form['receiver'] = $this->generateRandomString(20);
        $form['subject'] = $this->generateRandomString(80);
        $form['content'] = $this->generateRandomString(600);

        $crawler = $client->submit($form);

        $this->assertSuccessfulResponse($client);
        $email = $this->em->getRepository('ScrumManagerApiBundle:Email')->retrieveLast();
        $id = $email->getId();

        $crawler = $client->request('GET', '/api/testscreen/Email/RetrieveOne');

        $form = $crawler->selectButton('Retrieve email')->form();
        $form['id'] = $id;

        $crawler = $client->submit($form);
        $data = $this->assertSuccessfulResponse($client);

        $this->assertEquals($email->getSender(), $data['sender']);
        $this->assertEquals($email->getReceiver(), $data['receiver']);
        $this->assertEquals($email->getRead(), $data['read']);
        $this->assertEquals($email->getContent(), $data['content']);
        $this->assertEquals($email->getSent(), $data['sent']);
        $this->assertEquals($email->getCreatedAt()->format('Y-m-d H:i:s'), $data['created_at']);
        $this->assertEquals($email->getUpdatedAt()->format('Y-m-d H:i:s'), $data['updated_at']);
    }

    public function testRetrieveOne_InvalidId() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/testscreen/Email/CreateNewFromSystem');

        $form = $crawler->selectButton('Send email')->form();

        $form['receiver'] = $this->generateRandomString(20);
        $form['subject'] = $this->generateRandomString(80);
        $form['content'] = $this->generateRandomString(600);

        $crawler = $client->submit($form);

        $this->assertSuccessfulResponse($client);
        $email = $this->em->getRepository('ScrumManagerApiBundle:Email')->retrieveLast();
        $id = $email->getId() + 1;

        $crawler = $client->request('GET', '/api/testscreen/Email/RetrieveOne');

        $form = $crawler->selectButton('Retrieve email')->form();
        $form['id'] = $id;

        $crawler = $client->submit($form);
        $data = $this->assertErrorResponse($client);
        $data['status'] = ResponseEmailRetrieveFailure::$code;
    }

    public function testRetrieveOne_InvalidActive() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/testscreen/Email/CreateNewFromSystem');

        $form = $crawler->selectButton('Send email')->form();

        $form['receiver'] = $this->generateRandomString(20);
        $form['subject'] = $this->generateRandomString(80);
        $form['content'] = $this->generateRandomString(600);

        $crawler = $client->submit($form);

        $this->assertSuccessfulResponse($client);
        $email = $this->em->getRepository('ScrumManagerApiBundle:Email')->retrieveLast();
        $email->setActive(false);
        $this->em->getRepository('ScrumManagerApiBundle:Email')->updateOne($email);
        $id = $email->getId();

        $crawler = $client->request('GET', '/api/testscreen/Email/RetrieveOne');

        $form = $crawler->selectButton('Retrieve email')->form();
        $form['id'] = $id;

        $crawler = $client->submit($form);
        $data = $this->assertErrorResponse($client);
        $data['status'] = ResponseEmailRetrieveFailure::$code;
    }

    public function testMarkOneAsRead_Success() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/testscreen/Email/CreateNewFromSystem');

        $form = $crawler->selectButton('Send email')->form();

        $form['receiver'] = $this->generateRandomString(20);
        $form['subject'] = $this->generateRandomString(80);
        $form['content'] = $this->generateRandomString(600);

        $crawler = $client->submit($form);

        $this->assertSuccessfulResponse($client);
        $email = $this->em->getRepository('ScrumManagerApiBundle:Email')->retrieveLast();
        $id = $email->getId();

        $crawler = $client->request('GET', '/api/testscreen/Email/MarkOneAsRead');

        $form = $crawler->selectButton('Mark email')->form();
        $form['id'] = $id;

        $crawler = $client->submit($form);
        $data = $this->assertSuccessfulResponse($client);
    }

    public function testMarkOneAsRead_InvalidId() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/testscreen/Email/CreateNewFromSystem');

        $form = $crawler->selectButton('Send email')->form();

        $form['receiver'] = $this->generateRandomString(20);
        $form['subject'] = $this->generateRandomString(80);
        $form['content'] = $this->generateRandomString(600);

        $crawler = $client->submit($form);

        $this->assertSuccessfulResponse($client);
        $email = $this->em->getRepository('ScrumManagerApiBundle:Email')->retrieveLast();
        $id = $email->getId() + 1;

        $crawler = $client->request('GET', '/api/testscreen/Email/MarkOneAsRead');

        $form = $crawler->selectButton('Mark email')->form();
        $form['id'] = $id;

        $crawler = $client->submit($form);
        $data = $this->assertErrorResponse($client);
        $this->assertEquals($data['status'], ResponseEmailReadFailure::$code);
    }

    public function testMarkOneAsRead_InvalidActive() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/testscreen/Email/CreateNewFromSystem');

        $form = $crawler->selectButton('Send email')->form();

        $form['receiver'] = $this->generateRandomString(20);
        $form['subject'] = $this->generateRandomString(80);
        $form['content'] = $this->generateRandomString(600);

        $crawler = $client->submit($form);

        $this->assertSuccessfulResponse($client);
        $email = $this->em->getRepository('ScrumManagerApiBundle:Email')->retrieveLast();
        $email->setActive(false);
        $this->em->getRepository('ScrumManagerApiBundle:Email')->updateOne($email);
        $id = $email->getId();

        $crawler = $client->request('GET', '/api/testscreen/Email/MarkOneAsRead');

        $form = $crawler->selectButton('Mark email')->form();
        $form['id'] = $id;

        $crawler = $client->submit($form);
        $data = $this->assertErrorResponse($client);
        $this->assertEquals($data['status'], ResponseEmailReadFailure::$code);
    }
}