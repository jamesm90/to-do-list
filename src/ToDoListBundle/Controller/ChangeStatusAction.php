<?php

namespace ToDoListBundle\Controller;

use ToDoListBundle\Service\ItemService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class ChangeStatusAction
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
        $complete = $request->request->getInt('complete', 0);
        
        try {
            if ($complete) {
                $this->service->markCompleted($itemID);
            }
            else {
                $this->service->markIncomplete($itemID);
            }

            return new JsonResponse(true);
        } catch (Exception $e) {
            return new JsonResponse(false);
        }
    }
}
