<?php

namespace ScrumManager\ApiBundle\Service;


use Doctrine\ORM\EntityManager;
use ScrumManager\ApiBundle\Entity\Email;
use ScrumManager\ApiBundle\Repository\EmailRepository;
use Symfony\Component\Validator\Validator;

class EmailService extends BaseService {

    /**
     * Validator service.
     * @var \Symfony\Component\Validator\Validator
     */
    protected $validator;

    /**
     * @var EmailRepository
     */
    protected $repo;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * Class constructor.
     * @param Validator $validator The validator service.
     * @param EntityManager $em The Doctrine entity manager.
     */
    public function __construct($validator, EntityManager $em) {
        parent::__construct($em);

        $this->validator = $validator;
        $this->repo = $this->em->getRepository('ScrumManagerApiBundle:Email');
    }

    /**
     * Update the details of an email.
     * @param int $id The ID of the entry which needs to be updated.
     * @param array $params The parameters with which to update the email.
     * @return null|Email The entity that has been persisted to the database.
     */
    public function updateOne($id, array $params = array()) {
        // Find entity in database by identifier - if not found, return null.
        $criteria = array (
            'id' => $id
        );

        $email = $this->repo->findOneBy($criteria);

        if ($email === null) {
            return null;
        }

        // Update entity and persist it.
        $emailArray = $this->serializer->normalize($email);
        $params = array_merge($emailArray, $params);
        $params['createdAt'] = new \DateTime(strtotime($params['createdAt']['timestamp']));
        $params['updatedAt'] = new \DateTime(strtotime($params['updatedAt']['timestamp']));
        $email = $this->serializer->denormalize($params, 'ScrumManager\ApiBundle\Entity\Email');

        $entity = $this->repo->updateOne($email);
        return $this->serializer->normalize($entity);
    }

    /**
     * Creates a new email on the server.
     * @param string $sender The username of the sender.
     * @param string $receiver The username of the receiver.
     * @param string $subject The subject of the email.
     * @param string $content The content of the email.
     * @return null|Email The email entity which has been stored on the DB.
     */
    public function createOne($sender, $receiver, $subject, $content) {
        $email = new Email();

        $email->setSender($sender);
        $email->setReceiver($receiver);
        $email->setSubject($subject);
        $email->setContent($content);

        // Validate data and if it is incorrect, return null.
        $validatorErrors = $this->validator->validate($email);

        if (count($validatorErrors) > 0) {
            return null;
        }

        $entity = $this->repo->create($email);
        return $this->serializer->normalize($entity);
    }

    /**
     * Retrieve a single email from the system.
     * @param int $id The ID of the email that should be retrieved.
     * @return null|Email
     */
    public function retrieveOne($id) {
        $criteria = array(
            'active' => 1,
            'id' => $id
        );

        $email = $this->repo->findOneBy($criteria);

        if ($email === null) {
            return null;
        }

        return $this->serializer->normalize($email);
    }

    /**
     * Find all of the active entries in the system.
     * @return array Array containing all of the entries in the system.
     */
    public function retrieveAllActive() {
        $criteria = array(
            'active' => 1
        );

        $entries = $this->repo->findBy($criteria);

        foreach ($entries as $key => $entry) {
            $entries[$key] = $this->serializer->normalize($entry);
        }

        return $entries;
    }

    /**
     * Retrieve all of the email notifications for an account.
     * @param string $receiver The receiver username.
     * @return array Array containing all of the emails associated to an account.
     */
    public function retrieveAllReceivedForAccount($receiver) {
        $criteria = array(
            'active' => 1,
            'receiver' => $receiver
        );

        $entries = $this->repo->findBy($criteria);

        foreach ($entries as $key => $entry) {
            $entries[$key] = $this->serializer->normalize($entry);
        }

        return $entries;
    }

    /**
     * Mark a notification as read.
     * @param int $id The ID of the notification.
     * @return null|Email
     */
    public function markOneAsRead($id) {
        $criteria = array(
            'active' => 1,
            'id' => $id
        );

        $email = $this->repo->findOneBy($criteria);

        if ($email === null) {
            return null;
        }

        $email->setRead(true);
        $email = $this->repo->updateOne($email);

        return $this->serializer->normalize($email);
    }

    /**
     * Mark an entry as inactive in the system.
     * @param int $id The ID of the entry in the system.
     * @return null|Email The email entity that has been marked as inactive.
     */
    public function deleteOne($id) {
        $criteria = array(
            'active' => 1,
            'id' => $id
        );

        $email = $this->repo->findOneBy($criteria);

        if ($email === null) {
            return null;
        }

        $email->setActive(false);
        $email = $this->repo->updateOne($email);

        return $this->serializer->normalize($email);
    }
}