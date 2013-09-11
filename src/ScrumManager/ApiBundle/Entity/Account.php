<?php

namespace ScrumManager\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \DateTime;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class used to represent the Account entity.
 * @package ScrumManager\ApiBundle\Entity
 * @ORM\Entity(repositoryClass="ScrumManager\ApiBundle\Repository\AccountRepository")
 * @ORM\Table(name="account")
 */
class Account implements SerializableInterface {

    /**
     * Makes an Account entity from an array and return it.
     * @param array $params The parameters that should be used creating the entity.
     * @param Account $entity The entity which we should already use if we just want to update an entry with some data.
     * @return Account The entity that was generated after loading it from the array.
     */
    public static function makeFromArray($params, $entity = null) {
        if ($entity === null) {
            $entity = new self;
        }

        if (isset($params['username'])) {
            $entity->setUsername($params['username']);
        }

        if (isset($params['password'])) {
            $entity->setPassword($params['password']);
        }

        if (isset($params['seed'])) {
            $entity->setSeed($params['seed']);
        }

        if (isset($params['email'])) {
            $entity->setEmail($params['email']);
        }

        if (isset($params['first_name'])) {
            $entity->setFirstName($params['first_name']);
        }

        if (isset($params['last_name'])) {
            $entity->setLastName($params['last_name']);
        }

        if (isset($params['api_key'])) {
            $entity->setApiKey($params['api_key']);
        }

        if (isset($params['reset_token'])) {
            $entity->setResetToken($params['reset_token']);
        }

        if (isset($params['reset_initiated_at'])) {
            $entity->setResetInitiatedAt(new DateTime($params['reset_initiated_at']));
        }

        if (isset($params['created_at'])) {
            $entity->setCreatedAt(new DateTime($params['created_at']));
        }

        if (isset($params['updated_at'])) {
            $entity->setUpdatedAt(new DateTime($params['updated_at']));
        }

        return $entity;
    }

    /**
     * Return the entity by mapping its fields into an array.
     * @return array Array containing the mapping of the entity.
     */
    public function toArray() {
        $resetInitiatedAt = (is_null($this->getResetInitiatedAt())) ? null :
            $this->getResetInitiatedAt()->format('Y-m-d H:i:s');

        $data = array(
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
            'seed' => $this->getSeed(),
            'email' => $this->getEmail(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'api_key' => $this->getApiKey(),
            'reset_token' => $this->getResetToken(),
            'reset_initiated_at' => $resetInitiatedAt,
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $this->getUpdatedAt()->format('Y-m-d H:i:s')
        );

        return $data;
    }

    /**
     * Constructor for the class.
     */
    public function __construct() {
        $this->createdAt = new DateTime('now');
        $this->updatedAt = new DateTime('now');
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=80)
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=60)
     */
    protected $seed;

    /**
     * @ORM\Column(type="string", length=180)
     */
    protected $email;

    /**
     * @ORM\Column(name="first_name", type="string", length=80)
     */
    protected $firstName;

    /**
     * @ORM\Column(name="last_name", type="string", length=80)
     */
    protected $lastName;

    /**
     * @ORM\Column(name="api_key", type="string", length=160)
     */
    protected $apiKey;

    /**
     * @ORM\Column(name="reset_token", type="string", length=160, nullable = true)
     */
    protected $resetToken = null;

    /**
     * @ORM\Column(name="reset_initiated_at", type="datetime", nullable = true)
     */
    protected $resetInitiatedAt = null;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Account
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Account
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set seed
     *
     * @param string $seed
     * @return Account
     */
    public function setSeed($seed)
    {
        $this->seed = $seed;
    
        return $this;
    }

    /**
     * Get seed
     *
     * @return string 
     */
    public function getSeed()
    {
        return $this->seed;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Account
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return Account
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return Account
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set apiKey
     *
     * @param string $apiKey
     * @return Account
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    
        return $this;
    }

    /**
     * Get apiKey
     *
     * @return string 
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set resetToken
     *
     * @param string $resetToken
     * @return Account
     */
    public function setResetToken($resetToken)
    {
        $this->resetToken = $resetToken;
    
        return $this;
    }

    /**
     * Get resetToken
     *
     * @return string 
     */
    public function getResetToken()
    {
        return $this->resetToken;
    }

    /**
     * Set resetInitiatedAt
     *
     * @param \DateTime $resetInitiatedAt
     * @return Account
     */
    public function setResetInitiatedAt($resetInitiatedAt)
    {
        $this->resetInitiatedAt = $resetInitiatedAt;
    
        return $this;
    }

    /**
     * Get resetInitiatedAt
     *
     * @return \DateTime 
     */
    public function getResetInitiatedAt()
    {
        return $this->resetInitiatedAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Account
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Account
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}