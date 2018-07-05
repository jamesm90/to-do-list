<?php

namespace ToDoListBundle\Service;

use Doctrine\ORM\EntityManager;
use ToDoListBundle\Entity\Item;
use DateTime;
use Exception;

class ItemService
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * @return Item[]
     */
    public function getCompleteItems()
    {
        $itemRepo = $this->entityManager->getRepository(Item::class);
        return $itemRepo->findBy(
            ['completed' => true],
            ['dateCompleted' => 'DESC']
        );
    }  

    /**
     * @return Item[]
     */
    public function getIncompleteItems()
    {
        $itemRepo = $this->entityManager->getRepository(Item::class);
        $items = $itemRepo->findBy(
            ['completed' => false],
            ['dateDue' => 'ASC', 'id' => 'ASC']
        );

        // put null dates after due dates
        $nulls = [];
        $dates = [];
        foreach ($items as $index => $item) {
            if (is_null($item->getDateDue())) {
                $nulls[] = $item;
            }
            else {
                $dates[] = $item;
            }
        }

        return array_merge($dates, $nulls);
    }

    /**
     * @param  int $id
     * @return Item
     */
    public function getItem($id)
    {
        $itemRepo = $this->entityManager->getRepository(Item::class);
        return $itemRepo->findOneById($id);
    }

    /**
     * @param  string   $title 
     * @param  string   $description
     * @param  string   $dateDueStr
     * @param  int      $itemID
     * @return int|null
     */
    public function saveItem($title, $description, $dateDueStr, $itemID = null)
    {
        $errors = [];
        $dateDue = null;

        if ($dateDueStr != '') {
            try {
                $dateDue = DateTime::createFromFormat('Y-m-d', $dateDueStr);
                if (!is_object($dateDue)) {
                    throw new Exception('Not a date');
                }
            } catch (Exception $e) {
                $errors['Due Date'] = 'This is an invalid due date';
            }
        }

        if (empty($title)) {
            $errors['Title'] = 'This is a required field';
        }
        else if (strlen($title) > 255) {
            $errors['Title'] = 'This can be no longer than 255 characters';
        }

        if (empty($errors)) {
            if ($itemID != -1 && !empty($itemID)) {
                $item = $this->getItem($itemID);
            }
            else {
                $item = new Item();
            }

            $item->setDateCreated(new DateTime());
            if (!is_null($dateDue)) {
                $item->setDateDue($dateDue);
            }
            
            $item->setCompleted(false);
            $item->setTitle($title);
            $item->setDescription($description);

            $this->entityManager->persist($item);
            $this->entityManager->flush();
        }
        
        return $errors;
    }

    /**
     * @param int $itemID
     */
    public function markCompleted($itemID)
    {
        $item = $this->getItem($itemID);
        $item->setDateCompleted(new DateTime());
        $item->setCompleted(true);
    
        $this->entityManager->flush();
    }

    /**
     * @param int $itemID
     */
    public function markIncomplete($itemID)
    {
        $item = $this->getItem($itemID);
        $item->setDateCompleted(null);
        $item->setCompleted(false);
    
        $this->entityManager->flush();
    } 

    /**
     * @param int $itemID
     */
    public function removeItem($itemID)
    {
        $item = $this->getItem($itemID);
        
        $this->entityManager->remove($item);
        $this->entityManager->flush();
    }
}