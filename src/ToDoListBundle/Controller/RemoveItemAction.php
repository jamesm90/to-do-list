<?php

namespace ToDoListBundle\Controller;

use ToDoListBundle\Service\ItemService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class RemoveItemAction
{
    /**
     * @var ItemService
     */
    protected $service;

    /**
     * @param ItemService $service
     */
    public function __construct(
        ItemService $service
    ) {
        $this->service = $service;
    }

    /**
     * @param  Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        $itemID = $request->request->getInt('itemID');
        
        try {
            $this->service->removeItem($itemID);
            return new JsonResponse(true);
        } catch (Exception $e) {
            return new JsonResponse(false);
        }
    }
}
