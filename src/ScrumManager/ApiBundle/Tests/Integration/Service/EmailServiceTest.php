<?php

namespace ScrumManager\ApiBundle\Tests\Integration;


use ScrumManager\ApiBundle\Entity\Email;
use ScrumManager\ApiBundle\Repository\EmailRepository;
use ScrumManager\ApiBundle\Service\EmailService;
use ScrumManager\ApiBundle\Service\GeneralHelperService;
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
    protected $seedData;

    public function setUp() {
        parent::setUp();
        $this->repo = $this->em->getRepository('ScrumManagerApiBundle:Email');
        $this->validator = static::$kernel->getContainer()->get('validator');

        $this->seedData = array(
            'sender' => GeneralHelperService::generateRandomString(20),
            'receiver' => GeneralHelperService::generateRandomString(20),
            'subject' => GeneralHelperService::generateRandomString(100),
            'content' => GeneralHelperService::generateRandomString(620),
        );

        $this->emailService = new EmailService($this->validator, $this->em);
    }

    public function tearDown() {
        parent::tearDown();
        unset($this->repo);
        unset($this->validator);
        unset($this->seedData);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the data is valid.
     */
    public function testCreateOne_Valid() {
        $sender = $this->seedData['sender'];
        $receiver = $this->seedData['receiver'];
        $subject = $this->seedData['subject'];
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);

        $this->assertNotNull($email);
        $this->assertNotNull($email['id']);
        $this->assertTrue($email['active']);
        $this->assertFalse($email['read']);
        $this->assertFalse($email['sent']);
        $this->assertNotNull($email['createdAt']);
        $this->assertNotNull($email['updatedAt']);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the sender is blank.
     */
    public function testCreateOne_BlankSender() {
        $sender = '';
        $receiver = $this->seedData['receiver'];
        $subject = $this->seedData['subject'];
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the sender is not a string.
     */
    public function testCreateOne_InvalidSenderType() {
        $sender = 1;
        $receiver = $this->seedData['receiver'];
        $subject = $this->seedData['subject'];
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the sender is too short.
     */
    public function testCreateOne_SenderTooShort() {
        $sender = GeneralHelperService::generateRandomString(1);
        $receiver = $this->seedData['receiver'];
        $subject = $this->seedData['subject'];
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the sender is too long.
     */
    public function testCreateOne_SenderTooLong() {
        $sender = GeneralHelperService::generateRandomString(81);
        $receiver = $this->seedData['receiver'];
        $subject = $this->seedData['subject'];
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the receiver is blank.
     */
    public function testCreateOne_BlankReceiver() {
        $sender = $this->seedData['sender'];
        $receiver = '';
        $subject = $this->seedData['subject'];
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the receiver is not a string.
     */
    public function testCreateOne_InvalidReceiverType() {
        $sender = $this->seedData['sender'];
        $receiver = 1;
        $subject = $this->seedData['subject'];
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the receiver is too short.
     */
    public function testCreateOne_ReceiverTooShort() {
        $sender = $this->seedData['sender'];
        $receiver = GeneralHelperService::generateRandomString(1);
        $subject = $this->seedData['subject'];
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the receiver is too long.
     */
    public function testCreateOne_ReceiverTooLong() {
        $sender = $this->seedData['sender'];
        $receiver = GeneralHelperService::generateRandomString(81);
        $subject = $this->seedData['subject'];
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the subject is blank.
     */
    public function testCreateOne_BlankSubject() {
        $sender = $this->seedData['sender'];
        $receiver = $this->seedData['receiver'];
        $subject = '';
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the subject is not a string.
     */
    public function testCreateOne_InvalidSubjectType() {
        $sender = $this->seedData['sender'];
        $receiver = $this->seedData['receiver'];
        $subject = 1;
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the subject is too long.
     */
    public function testCreateOne_SubjectTooLong() {
        $sender = $this->seedData['sender'];
        $receiver = $this->seedData['receiver'];
        $subject = GeneralHelperService::generateRandomString(181);
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the content is blank.
     */
    public function testCreateOne_BlankContent() {
        $sender = $this->seedData['sender'];
        $receiver = $this->seedData['receiver'];
        $subject = $this->seedData['subject'];
        $content = '';

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for creating a new entry, when the content is not text.
     */
    public function testCreateOne_InvalidContentType() {
        $sender = $this->seedData['sender'];
        $receiver = $this->seedData['receiver'];
        $subject = $this->seedData['subject'];
        $content = 1;

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->assertNull($email);
    }

    /**
     * Test the behaviour of the method for retrieving an entry, when all the fields are valid.
     */
    public function testRetrieveOne_Valid() {
        $sender = $this->seedData['sender'];
        $receiver = $this->seedData['receiver'];
        $subject = $this->seedData['subject'];
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $retrievedEmail = $this->emailService->retrieveOne($email['id']);

        $this->assertEquals($email, $retrievedEmail);
    }

    /**
     * Test the behaviour of the method when retrieving an entry with an invalid ID.
     */
    public function testRetrieveOne_InvalidId() {
        $sender = $this->seedData['sender'];
        $receiver = $this->seedData['receiver'];
        $subject = $this->seedData['subject'];
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $retrievedEmail = $this->emailService->retrieveOne($email['id'] + 1);

        $this->assertNull($retrievedEmail);
    }

    /**
     * Test the behaviour of the method when retrieving an entry with an invalid active status.
     */
    public function testRetrieveOne_InvalidActive() {
        $sender = $this->seedData['sender'];
        $receiver = $this->seedData['receiver'];
        $subject = $this->seedData['subject'];
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $email['active'] = false;
        $email = $this->emailService->updateOne($email['id'], $email);

        $retrievedEmail = $this->emailService->retrieveOne($email['id']);

        $this->assertNull($retrievedEmail);
    }

    public function testMarkOneAsRead_Valid() {
        $sender = $this->seedData['sender'];
        $receiver = $this->seedData['receiver'];
        $subject = $this->seedData['subject'];
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $email = $this->emailService->markOneAsRead($email['id']);
        $this->assertNotNull($email);
        $this->assertEquals(true, $email['read']);
    }

    public function testMarkOneAsRead_InvalidId() {
        $sender = $this->seedData['sender'];
        $receiver = $this->seedData['receiver'];
        $subject = $this->seedData['subject'];
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $email = $this->emailService->markOneAsRead($email['id'] + 1);
        $this->assertNull($email);
    }

    public function testMarkOneAsRead_InvalidActive() {
        $sender = $this->seedData['sender'];
        $receiver = $this->seedData['receiver'];
        $subject = $this->seedData['subject'];
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $email['active'] = false;
        $email = $this->emailService->updateOne($email['id'], $email);

        $email = $this->emailService->markOneAsRead($email['id']);
        $this->assertNull($email);
    }

    public function testRetrieveAllActive_Valid() {
        $sender = $this->seedData['sender'];
        $receiver = $this->seedData['receiver'];
        $subject = $this->seedData['subject'];
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);

        $emailList = $this->emailService->retrieveAllActive();

        $this->assertGreaterThan(0, count($emailList));

        $foundEntry = false;
        foreach($emailList as $emailEntry) {
            if ($emailEntry['id'] == $email['id']) {
                $foundEntry = true;

                $this->assertEquals($emailEntry['sender'], $email['sender']);
                $this->assertEquals($emailEntry['receiver'], $email['receiver']);
                $this->assertEquals($emailEntry['subject'], $email['subject']);
                $this->assertEquals($emailEntry['content'], $email['content']);
                $this->assertEquals($emailEntry['read'], $email['read']);
                $this->assertEquals($emailEntry['sent'], $email['sent']);
            }
        }

        $this->assertTrue($foundEntry);
    }

    public function testRetrieveAllActive_InvalidActive() {
        $sender = $this->seedData['sender'];
        $receiver = $this->seedData['receiver'];
        $subject = $this->seedData['subject'];
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $email['active'] = false;
        $email = $this->emailService->updateOne($email['id'], $email);

        $emailList = $this->emailService->retrieveAllActive();

        $foundEntry = false;
        foreach($emailList as $emailEntry) {
            if ($emailEntry['id'] == $email['id']) {
                $foundEntry = true;

                $this->assertEquals($emailEntry['sender'], $email['sender']);
                $this->assertEquals($emailEntry['receiver'], $email['receiver']);
                $this->assertEquals($emailEntry['subject'], $email['subject']);
                $this->assertEquals($emailEntry['content'], $email['content']);
                $this->assertEquals($emailEntry['read'], $email['read']);
                $this->assertEquals($emailEntry['sent'], $email['sent']);
            }
        }

        $this->assertFalse($foundEntry);
    }

    public function testDeleteOne_Valid() {
        $sender = $this->seedData['sender'];
        $receiver = $this->seedData['receiver'];
        $subject = $this->seedData['subject'];
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->emailService->deleteOne($email['id']);
        $retrievedEmail = $this->emailService->retrieveOne($email['id']);

        $this->assertNull($retrievedEmail);
    }

    public function testDeleteOne_InvalidId() {
        $sender = $this->seedData['sender'];
        $receiver = $this->seedData['receiver'];
        $subject = $this->seedData['subject'];
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $this->emailService->deleteOne($email['id'] + 100);
        $retrievedEmail = $this->emailService->retrieveOne($email['id']);

        $this->assertNotNull($retrievedEmail);
        $this->assertEquals($email['id'], $retrievedEmail['id']);
        $this->assertEquals($email['active'], $retrievedEmail['active']);
        $this->assertEquals($email['sent'], $retrievedEmail['sent']);
        $this->assertEquals($email['read'], $retrievedEmail['read']);
        $this->assertEquals($email['sender'], $retrievedEmail['sender']);
        $this->assertEquals($email['receiver'], $retrievedEmail['receiver']);
//        $this->assertEquals($email['createdAt'], $retrievedEmail['createdAt']);
    }

    public function testDeleteOne_InvalidActive() {
        $sender = $this->seedData['sender'];
        $receiver = $this->seedData['receiver'];
        $subject = $this->seedData['subject'];
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $email['active'] = false;
        $email = $this->emailService->updateOne($email['id'], $email);

        $deletedEmail = $this->emailService->deleteOne($email['id']);

        $this->assertNull($deletedEmail);
    }

    public function testRetrieveAllReceivedForAccount_Valid() {
        $sender = $this->seedData['sender'];
        $receiver = $this->seedData['receiver'];
        $subject = $this->seedData['subject'];
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $emailList = $this->emailService->retrieveAllReceivedForAccount($email['receiver']);

        $this->assertGreaterThan(0, count($emailList));

        $foundEmail = false;
        foreach($emailList as $emailEntry) {
            if ($emailEntry['id'] == $email['id']) {
                $foundEmail = true;

                $this->assertEquals($emailEntry['sender'], $email['sender']);
                $this->assertEquals($emailEntry['receiver'], $email['receiver']);
                $this->assertEquals($emailEntry['subject'], $email['subject']);
                $this->assertEquals($emailEntry['content'], $email['content']);
                $this->assertEquals($emailEntry['read'], $email['read']);
                $this->assertEquals($emailEntry['sent'], $email['sent']);
            }
        }

        $this->assertTrue($foundEmail);
    }

    public function testRetrieveAllReceivedForAccount_InvalidReceiver() {
        $sender = $this->seedData['sender'];
        $receiver = $this->seedData['receiver'];
        $subject = $this->seedData['subject'];
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $emailList = $this->emailService->retrieveAllReceivedForAccount($email['receiver'] . GeneralHelperService::generateRandomString(15));

        $this->assertEquals(0, count($emailList));
    }

    public function testRetrieveAllReceivedForAccount_InvalidActive() {
        $sender = $this->seedData['sender'];
        $receiver = $this->seedData['receiver'];
        $subject = $this->seedData['subject'];
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $email['active'] = false;
        $this->emailService->deleteOne($email['id']);
        $emailList = $this->emailService->retrieveAllReceivedForAccount($email['receiver']);

        $this->assertEquals(0, count($emailList));
    }

    public function testUpdateOne_Invalid() {
        $sender = $this->seedData['sender'];
        $receiver = $this->seedData['receiver'];
        $subject = $this->seedData['subject'];
        $content = $this->seedData['content'];

        $email = $this->emailService->createOne($sender, $receiver, $subject, $content);
        $emailRetrieved = $this->emailService->updateOne($email['id'] + 100, array());

        $this->assertNull($emailRetrieved);
    }
}