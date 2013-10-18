<?php

namespace ScrumManager\ApiBundle\Service;


use Doctrine\ORM\EntityManager;
use Doctrine\Tests\Common\Annotations\Ticket\Doctrine\ORM\Mapping\Entity;
use ScrumManager\ApiBundle\Entity\Account;
use ScrumManager\ApiBundle\Repository\AccountRepository;
use Symfony\Component\Serializer\Serializer;
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
        $account = $this->serializer->denormalize($params, 'ScrumManager\ApiBundle\Entity\Account');
        $account = $this->setGenerationData($account);

        // Validate data and if it is incorrect, return null.
        $validatorErrors = $this->validator->validate($account);

        if (count($validatorErrors) > 0) {
            return null;
        }

        $entity = $this->repo->create($account);
        return $this->serializer->normalize($entity);
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

        $entity = $this->repo->findByUsernameAndPassword($username, $password, $seed);
        return $this->serializer->normalize($entity);
    }

    /**
     * Update the details of an account.
     * @param string $apiKey The API key that is associated to the account.
     * @param array $params The parameters with which to update the account.
     * @return null|Account The entity that has been persisted to the database.
     */
    public function updateOne($apiKey, array $params = array()) {
        // Find entity in database by identifier - if not found, return null.
        $criteria = array(
            'apiKey' => $apiKey,
            'active' => true
        );

        $account = $this->repo->findOneBy($criteria);

        if ($account === null) {
            return null;
        }

        // Update entity and persist it.
        $accountArray = $this->serializer->normalize($account);
        $accountArray['createdAt'] = new DateTime(strtotime($accountArray['createdAt']['timestamp']));
        $accountArray['updatedAt'] = new DateTime(strtotime($accountArray['updatedAt']['timestamp']));
        $params = array_merge($accountArray, $params);
        $account = $this->serializer->denormalize($params, 'ScrumManager\ApiBundle\Entity\Account');

        $entity = $this->repo->updateOne($account);
        return $this->serializer->normalize($entity);
    }

    /**
     * Recreate the password of an account, via its seed.
     * @param Account $account The account entity that should be updated.
     * @param string $password The new password that should be set on an account.
     * @return null|Account The new account entity that has been persisted.
     */
    protected function encryptPassword(Account $account, $password) {
        $seed = $account->getSeed();
        $account->setPassword(hash('sha512', $seed . $password));

        return $account;
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

    /**
     * Change the password for an account in the database.
     * @param string $apiKey The API key associated with the account.
     * @param string $oldPassword The old password associated with the account.
     * @param string $newPassword The new password associated with the account.
     * @return null|Account The account entity that has been updated.
     */
    public function changePassword($apiKey, $oldPassword, $newPassword) {
        // Find entity in database by identifier - if not found, return null.
        $criteria = array(
            'apiKey' => $apiKey,
            'active' => true
        );

        $account = $this->repo->findOneBy($criteria);

        if ($account === null) {
            return null;
        }

        // Check if old password is valid.
        $seed = $account->getSeed();
        $encryptedPassword = $account->getPassword();

        if ($encryptedPassword === hash('sha512', $seed . $oldPassword)) {
            $account = $this->encryptPassword($account, $newPassword);
            $entity = $this->repo->updateOne($account);
            return $this->serializer->normalize($entity);
        }

        return null;
    }

    /**
     * Retrieve a single account from the database.
     * @param string $username The username associated to the account.
     * @return null|Account The account entity that has been found.
     */
    public function retrieveOne($username) {
        // Find entity in database by identifier - if not found, return null.
        $criteria = array(
            'username' => $username,
            'active' => true
        );

        $account = $this->repo->findOneBy($criteria);

        if ($account === null) {
            return null;
        }

        return $this->serializer->normalize($account);
    }

    /**
     * Deactivate an account that is active in the database.
     * @param string $apiKey The API key of the account that needs to be disabled.
     * @return null|Account The account that was deactivated.
     */
    public function deactivateAccount($apiKey) {
        // Find entity in database by identifier - if not found, return null.
        $criteria = array(
            'apiKey' => $apiKey,
            'active' => true
        );

        $account = $this->repo->findOneBy($criteria);

        if ($account === null) {
            return null;
        }

        $account->setActive(false);
        $entity = $this->repo->updateOne($account);

        return $this->serializer->normalize($entity);
    }

    /**
     * Reset the password for an account, by setting the resetInitiatedAt property, and by generating a
     * random reset token.
     * @param string $apiKey The API key that should be used for locating the account.
     * @return null|Account The account entity that has been updated.
     */
    public function resetPassword($apiKey) {
        // Find entity in database by identifier - if not found, return null.
        $criteria = array(
            'apiKey' => $apiKey,
            'active' => true
        );

        $account = $this->repo->findOneBy($criteria);

        if ($account === null) {
            return null;
        }

        $account = $this->generateResetDetails($account);

        $entity = $this->repo->updateOne($account);

        return $this->serializer->normalize($entity);
    }

    /**
     * Generate reset details for an Account.
     * @param Account $account The account that should be affected.
     * @return Account The account entity after its reset details have been set.
     */
    protected function generateResetDetails(Account $account) {
        $account->setResetInitiatedAt(new DateTime('now'));
        $account->setResetToken(hash('sha512', $this->generateRandomString(10)));

        return $account;
    }

    /**
     * Having asked for a password reset, change the password of the user via the reset token.
     * @param string $apiKey The API key of the account..
     * @param string $resetToken The reset token of the account.
     * @param string $newPassword The password that should be used in combination with the sed.
     * @return null|Account The account with the new password.
     */
    public function newPassword($apiKey, $resetToken, $newPassword) {
        // Find entity in database by identifier - if not found, return null.
        $criteria = array(
            'apiKey' => $apiKey,
            'active' => true,
            'resetToken' => $resetToken
        );

        $account = $this->repo->findOneBy($criteria);

        if ($account === null) {
            return null;
        }

        if ($account->getResetToken() === null) {
            return null;
        }

        $seed = $account->getSeed();
        $account->setPassword(hash('sha512', $seed . $newPassword));
        $account->setResetToken(null);
        $account->setResetInitiatedAt(null);

        $entity = $this->repo->updateOne($account);
        return $this->serializer->normalize($entity);
    }
}