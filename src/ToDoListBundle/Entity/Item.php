<?php

namespace ToDoListBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ambta\DoctrineEncryptBundle\Configuration\Encrypted;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="Item")
 */
class Item
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $dateCreated;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $dateDue;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $dateCompleted;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $completed;

    /**
     * @ORM\Column(type="string", length=255)
     * @Encrypted
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Encrypted
     */
    protected $description;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param DateTime $dateCreated
     */
    public function setDateCreated(DateTime $dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return DateTime
     */
    public function getDateDue()
    {
        return $this->dateDue;
    }

    /**
     * @param DateTime $dateDue
     */
    public function setDateDue(DateTime $dateDue = null)
    {
        $this->dateDue = $dateDue;
    }

    /**
     * @return DateTime
     */
    public function getDateCompleted()
    {
        return $this->dateCompleted;
    }

    /**
     * @param DateTime $dateCompleted
     */
    public function setDateCompleted(DateTime $dateCompleted = null)
    {
        $this->dateCompleted = $dateCompleted;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return bool
     */
    public function isCompleted()
    {
        return $this->completed;
    }

    /**
     * @param boolean $completed
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;
    }
}
