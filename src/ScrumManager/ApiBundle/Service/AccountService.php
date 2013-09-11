<?php

namespace ScrumManager\ApiBundle\Service;


use Doctrine\ORM\EntityManager;
use Doctrine\Tests\Common\Annotations\Ticket\Doctrine\ORM\Mapping\Entity;
use ScrumManager\ApiBundle\Entity\Account;
use ScrumManager\ApiBundle\Repository\AccountRepository;
use Symfony\Component\Validator\Validator;
use \DateTime;

class AccountService extends BaseService {

    /**
     * Validator service.
     * @var \Symfony\Component\Validator\Validator
     */
    protected $validator;

    /**
     * @var AccountRepository
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
        $this->repo = $this->em->getRepository('ScrumManagerApiBundle:Account');
    }

    /**
     * Register a new account into the system.
     * @param array $params The parameters that should be used for creating the account.
     * @return null|Account The account that has been generated.
     */
    public function register(array $params = array()) {
        $account = Account::makeFromArray($params);
        $account = $this->setGenerationData($account);

        // Validate data and if it is incorrect, return null.
        $validatorErrors = $this->validator->validate($account);

        if (count($validatorErrors) > 0) {
            return null;
        }

        return $this->repo->create($account);
    }

    /**
     * Attempt to retrieve an account in order to log them into the system.
     * @param string $username The username that the account is associated to.
     * @param string $password The password that the account is associated to.
     * @return null|Account The account entity that is found.
     */
    public function login($username, $password) {
        $accountWithSeed = $this->repo->findOneBy(array('username' => $username));

        if ($accountWithSeed === null) {
            return null;
        }

        $seed = $accountWithSeed->getSeed();

        return $this->repo->findByUsernameAndPassword($username, $password, $seed);
    }

    /**
     * Set default data and generate account encoded data.
     * @param Account $account The account that should be affected.
     * @return Account The account instance after it has been manipulated.
     */
    public function setGenerationData(Account $account) {
        $seed = $this->generateRandomString(20);
        $account->setSeed($seed);

        if ($account->getPassword() == null) {
            $account->setPassword($this->generateRandomString(30));
        }

        $password = hash('sha512', $seed . $account->getPassword());
        $account->setPassword($password);

        $apiKey = hash('sha512', $this->generateRandomString(20));
        $account->setApiKey($apiKey);

        return $account;
    }
}