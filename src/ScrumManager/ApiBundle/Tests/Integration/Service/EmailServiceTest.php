<?php

namespace ScrumManager\ApiBundle\Tests\Integration;


use ScrumManager\ApiBundle\Entity\Email;
use ScrumManager\ApiBundle\Repository\EmailRepository;
use ScrumManager\ApiBundle\Service\EmailService;
use Symfony\Component\Validator\Validator;

class EmailServiceTest extends BaseIntegrationTestCase {

    /**
     * @var EmailRepository
     */
    protected $repo;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @var EmailService;
     */
    protected $emailService;

    /**
     * @var array
     */
    protected $data;

    public function setUp() {
        parent::setUp();
        $this->repo = $this->em->getRepository('ScrumManagerApiBundle:Email');
        $this->validator = static::$kernel->getContainer()->get('validator');

        $this->data = array(
            'sender' => $this->generateRandomString(20),
            'receiver' => $this->generateRandomString(20),
            'subject' => $this->generateRandomString(100),
            'content' => $this->generateRandomString(620),
        );

        $this->emailService = new EmailService($this->validator, $this->em);
    }

    public function tearDown() {
        parent::tearDown();
        unset($this->repo);
        unset($this->validator);
        unset($this->data);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the data is valid.
     */
    public function testCreateOne_Valid() {
        $sender = $this->data['sender'];
        $receiver = $this->data['receiver'];
        $subject = $this->data['subject'];
        $content = $this->data['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);

        $this->assertNotNull($email);
        $this->assertNotNull($email->getId());
        $this->assertTrue($email->getActive());
        $this->assertFalse($email->getRead());
        $this->assertFalse($email->getSent());
        $this->assertNotNull($email->getCreatedAt());
        $this->assertNotNull($email->getUpdatedAt());
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the sender is blank.
     */
    public function testCreateOne_BlankSender() {
        $sender = '';
        $receiver = $this->data['receiver'];
        $subject = $this->data['subject'];
        $content = $this->data['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the sender is not a string.
     */
    public function testCreateOne_InvalidSenderType() {
        $sender = 1;
        $receiver = $this->data['receiver'];
        $subject = $this->data['subject'];
        $content = $this->data['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the sender is too short.
     */
    public function testCreateOne_SenderTooShort() {
        $sender = $this->generateRandomString(1);
        $receiver = $this->data['receiver'];
        $subject = $this->data['subject'];
        $content = $this->data['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the sender is too long.
     */
    public function testCreateOne_SenderTooLong() {
        $sender = $this->generateRandomString(81);
        $receiver = $this->data['receiver'];
        $subject = $this->data['subject'];
        $content = $this->data['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the receiver is blank.
     */
    public function testCreateOne_BlankReceiver() {
        $sender = $this->data['sender'];
        $receiver = '';
        $subject = $this->data['subject'];
        $content = $this->data['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the receiver is not a string.
     */
    public function testCreateOne_InvalidReceiverType() {
        $sender = $this->data['sender'];
        $receiver = 1;
        $subject = $this->data['subject'];
        $content = $this->data['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the receiver is too short.
     */
    public function testCreateOne_ReceiverTooShort() {
        $sender = $this->data['sender'];
        $receiver = $this->generateRandomString(1);
        $subject = $this->data['subject'];
        $content = $this->data['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the receiver is too long.
     */
    public function testCreateOne_ReceiverTooLong() {
        $sender = $this->data['sender'];
        $receiver = $this->generateRandomString(81);
        $subject = $this->data['subject'];
        $content = $this->data['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the subject is blank.
     */
    public function testCreateOne_BlankSubject() {
        $sender = $this->data['sender'];
        $receiver = $this->data['receiver'];
        $subject = '';
        $content = $this->data['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the subject is not a string.
     */
    public function testCreateOne_InvalidSubjectType() {
        $sender = $this->data['sender'];
        $receiver = $this->data['receiver'];
        $subject = 1;
        $content = $this->data['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the subject is too long.
     */
    public function testCreateOne_SubjectTooLong() {
        $sender = $this->data['sender'];
        $receiver = $this->data['receiver'];
        $subject = $this->generateRandomString(181);
        $content = $this->data['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the content is blank.
     */
    public function testCreateOne_BlankContent() {
        $sender = $this->data['sender'];
        $receiver = $this->data['receiver'];
        $subject = $this->data['subject'];
        $content = '';

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the content is not text.
     */
    public function testCreateOne_InvalidContentType() {
        $sender = $this->data['sender'];
        $receiver = $this->data['receiver'];
        $subject = $this->data['subject'];
        $content = 1;

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for retrieving an entry, when all the fields are valid.
     */
    public function testRetrieveOne_Valid() {
        $sender = $this->data['sender'];
        $receiver = $this->data['receiver'];
        $subject = $this->data['subject'];
        $content = $this->data['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $retrievedEmail = $this->emailService->retrieveOne($email->getId());

        $this->assertEquals($email, $retrievedEmail);
    }

    /**
     * Test the behaviour of the method when retrieving an entry with an invalid ID.
     */
    public function testRetrieveOne_InvalidId() {
        $sender = $this->data['sender'];
        $receiver = $this->data['receiver'];
        $subject = $this->data['subject'];
        $content = $this->data['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $retrievedEmail = $this->emailService->retrieveOne($email->getId() + 1);

        $this->assertNull($retrievedEmail);
    }

    /**
     * Test the behaviour of the method when retrieving an entry with an invalid active status.
     */
    public function testRetrieveOne_InvalidActive() {
        $sender = $this->data['sender'];
        $receiver = $this->data['receiver'];
        $subject = $this->data['subject'];
        $content = $this->data['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $email->setActive(false);
        $this->repo->updateOne($email);

        $retrievedEmail = $this->emailService->retrieveOne($email->getId());

        $this->assertNull($retrievedEmail);
    }
}