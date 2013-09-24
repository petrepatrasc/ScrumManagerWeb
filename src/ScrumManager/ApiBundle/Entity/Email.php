<?php

namespace ScrumManager\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * Class used to represent the Email entity.
 * @package ScrumManager\ApiBundle\Entity
 * @ORM\Entity(repositoryClass="ScrumManager\ApiBundle\Repository\EmailRepository")
 * @ORM\Table(name="email")
 */
class Email implements SerializableInterface {

    /**
     * Makes an Email entity from an array and return it.
     * @param array $params The parameters that should be used creating the entity.
     * @param Email $entity The entity which we should already use if we just want to update an entry with some data.
     * @return Email The entity that was generated after loading it from the array.
     */
    public static function makeFromArray($params, $entity = null) {
        if ($entity === null) {
            $entity = new self;
        }

        if (isset($params['sender'])) {
            $entity->setSender($params['sender']);
        }

        if (isset($params['receiver'])) {
            $entity->setReceiver($params['receiver']);
        }

        if (isset($params['subject'])) {
            $entity->setSubject($params['subject']);
        }

        if (isset($params['read'])) {
            $entity->setRead((bool) $params['read']);
        }

        if (isset($params['content'])) {
            $entity->setContent($params['content']);
        }

        if (isset($params['sent'])) {
            $entity->setSent((bool) $params['sent']);
        }

        if (isset($params['created_at'])) {
            $entity->setCreatedAt(new DateTime($params['created_at']));
        }

        if (isset($params['updated_at'])) {
            $entity->setUpdatedAt(new DateTime($params['updated_at']));
        }

        if (isset($params['active'])) {
            $entity->setActive((bool) $params['active']);
        }

        return $entity;
    }

    /**
     * Return the entity by mapping its fields into an array.
     * @return array Array containing the mapping of the entity.
     */
    public function toArray() {
        $data = array(
            'id' => $this->getId(),
            'sender' => $this->getSender(),
            'receiver' => $this->getReceiver(),
            'subject' => $this->getSubject(),
            'read' => $this->getRead(),
            'content' => $this->getContent(),
            'sent' => $this->getSent(),
            'active' => $this->getActive(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt()
        );

        return $data;
    }

    /**
     * Return the entity by mapping its fields into an array, however only returning data that should be returned via
     * the API and that is considered "safe" for interception.
     * @return array Array containing the mapping of the entity.
     */
    public function toSafeArray() {
        $data = array(
            'sender' => $this->getSender(),
            'receiver' => $this->getReceiver(),
            'subject' => $this->getSubject(),
            'read' => $this->getRead(),
            'content' => $this->getContent(),
            'sent' => $this->getSent(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt()
        );

        return $data;
    }

    /**
     * Class constructor for new entities, setting some implicit defaults.
     */
    public function __construct() {
        $this->setCreatedAt(new DateTime('now'));
        $this->setUpdatedAt(new DateTime('now'));
        $this->setActive(true);
        $this->setSent(false);
        $this->setRead(false);
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
    protected $sender;

    /**
     * @ORM\Column(type="string", length=80)
     */
    protected $receiver;

    /**
     * @ORM\Column(type="string", length=180)
     */
    protected $subject;

    /**
     * @ORM\Column(name="`read`", type="boolean")
     */
    protected $read;

    /**
     * @ORM\Column(type="text")
     */
    protected $content;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $sent;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $active;

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
     * Set sender
     *
     * @param string $sender
     * @return Email
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    
        return $this;
    }

    /**
     * Get sender
     *
     * @return string 
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set receiver
     *
     * @param string $receiver
     * @return Email
     */
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
    
        return $this;
    }

    /**
     * Get receiver
     *
     * @return string 
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return Email
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    
        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set read
     *
     * @param boolean $read
     * @return Email
     */
    public function setRead($read)
    {
        $this->read = $read;
    
        return $this;
    }

    /**
     * Get read
     *
     * @return boolean 
     */
    public function getRead()
    {
        return $this->read;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Email
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set sent
     *
     * @param boolean $sent
     * @return Email
     */
    public function setSent($sent)
    {
        $this->sent = $sent;
    
        return $this;
    }

    /**
     * Get sent
     *
     * @return boolean 
     */
    public function getSent()
    {
        return $this->sent;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Email
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
     * @return Email
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

    /**
     * Set active
     *
     * @param boolean $active
     * @return Email
     */
    public function setActive($active)
    {
        $this->active = $active;
    
        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }
}