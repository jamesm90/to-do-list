<?php

namespace ToDoListBundle\Controller;

use ToDoListBundle\Service\ItemService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SaveItemAction
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
        $itemID = $request->request->getInt('itemID', -1);
        $title = trim($request->request->get('title', ''));
        $description = trim($request->request->get('desc', ''));
        $dueDateStr = trim($request->request->get('date', ''));

        $errors = $this->service->saveItem($title, $description, $dueDateStr, $itemID);

        return new JsonResponse([
            'errors' => $errors
        ]);
    }
}
