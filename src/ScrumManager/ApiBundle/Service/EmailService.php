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

        return $this->repo->create($email);
    }
}