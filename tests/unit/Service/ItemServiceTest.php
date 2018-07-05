<?php

namespace ToDoListBundle\Service;

use PHPUnit\Framework\TestCase;
use Mockery;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use ToDoListBundle\Entity\Item;

class ItemServiceTest extends TestCase
{
    /**
     * @var EntityManager|Mock
     */
    protected $entityManager;

    /**
     * @var EntityRepository|Mock
     */
    protected $itemRepo;

    /**
     * @var ItemService
     */
    protected $service;

    public function setUp()
    {
        $this->itemRepo = Mockery::mock(EntityRepository::class);

        $this->entityManager = Mockery::mock(EntityManager::class);
        $this->entityManager->shouldReceive('getRepository')
            ->with(Item::class)
            ->andReturn($this->itemRepo);
        
        $this->service = new ItemService($this->entityManager);
    }  

    public function testGetCompleteItems()
    {
        $item = new Item();
        $this->itemRepo->shouldReceive('findBy')
            ->once()
            ->with(['completed' => true], ['dateCompleted' => 'DESC'])
            ->andReturn([$item]);

        $this->assertEquals([$item], $this->service->getCompleteItems());
    }

    public function testGetIncompleteItems()
    {
        $item1 = new Item();

        $item2 = new Item();
        $item2->setDateDue(new DateTime());

        $this->itemRepo->shouldReceive('findBy')
            ->once()
            ->with(['completed' => false], ['dateDue' => 'ASC', 'id' => 'ASC'])
            ->andReturn([$item1, $item2]);

        $this->assertEquals([$item2, $item1], $this->service->getIncompleteItems());
    }

    public function testGetItem()
    {
        $item = new Item();
        $this->itemRepo->shouldReceive('findOneById')
            ->once()
            ->with(10)
            ->andReturn($item);

        $this->assertEquals($item, $this->service->getItem(10));
    }

    public function testSaveItemIncorrectDate()
    {
        $this->entityManager->shouldReceive('flush')
            ->never();

        $this->assertEquals(
            ['Due Date' => 'This is an invalid due date'],
            $this->service->saveItem('Test', '', 'Test')
        );
    }    

    public function testSaveItemEmptyTitle()
    {
        $this->entityManager->shouldReceive('flush')
            ->never();

        $this->assertEquals(
            ['Title' => 'This is a required field'],
            $this->service->saveItem('', '', '2018-01-01')
        );
    }

        public function testSaveItemLongTitle()
    {
        $this->entityManager->shouldReceive('flush')
            ->never();

        $this->assertEquals(
            ['Title' => 'This can be no longer than 255 characters'],
            $this->service->saveItem('123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456', '', '2018-01-01')
        );
    }


    public function testSaveItem()
    {
        $this->entityManager->shouldReceive('persist')
            ->once();
        $this->entityManager->shouldReceive('flush')
            ->once();

        $this->assertEquals(
            [],
            $this->service->saveItem('Test', '', '2018-01-01')
        );
    } 

    public function testSaveItemUpdate()
    {
        $item = new Item();
        $this->itemRepo->shouldReceive('findOneById')
            ->once()
            ->with(10)
            ->andReturn($item);

        $this->entityManager->shouldReceive('persist')
            ->once();
        $this->entityManager->shouldReceive('flush')
            ->once();

        $this->assertEquals(
            [],
            $this->service->saveItem('Test', '', '2018-01-01', 10)
        );
    } 

    public function testMarkCompleted()
    {
        $item = Mockery::mock(Item::class);
        $this->itemRepo->shouldReceive('findOneById')
            ->once()
            ->with(10)
            ->andReturn($item);

        $item->shouldReceive('setDateCompleted')
            ->once();
        $item->shouldReceive('setCompleted')
            ->with(true)
            ->once();

        $this->entityManager->shouldReceive('flush')
            ->once();

        $this->assertNull($this->service->markCompleted(10));
    } 

    public function testMarkIncomplete()
    {
        $item = Mockery::mock(Item::class);
        $this->itemRepo->shouldReceive('findOneById')
            ->once()
            ->with(10)
            ->andReturn($item);

        $item->shouldReceive('setDateCompleted')
            ->with(null)
            ->once();
        $item->shouldReceive('setCompleted')
            ->with(false)
            ->once();

        $this->entityManager->shouldReceive('flush')
            ->once();

        $this->assertNull($this->service->markIncomplete(10));
    } 

    public function testRemoveItem()
    {
        $item = new Item();
        $this->itemRepo->shouldReceive('findOneById')
            ->once()
            ->with(10)
            ->andReturn($item);

        $this->entityManager->shouldReceive('remove')
            ->with($item)
            ->once();

        $this->entityManager->shouldReceive('flush')
            ->once();

        $this->assertNull($this->service->removeItem(10));
    }     
}