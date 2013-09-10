<?php

namespace ScrumManager\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \DateTime;

/**
 * Class used to represent the Account entity.
 * @package ScrumManager\ApiBundle\Entity
 * @ORM\Entity(repositoryClass="ScrumManager\ApiBundle\Repository\AccountRepository")
 * @ORM\Table(name="account")
 */
class Account {

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
     * @ORM\Column(name="reset_token", type="string", length=160)
     */
    protected $resetToken = null;

    /**
     * @ORM\Column(name="reset_initiated_at", type="datetime")
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