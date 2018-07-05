<?php

namespace ToDoListBundle\Controller;

use ToDoListBundle\Service\ItemService;
use ToDoListBundle\Utilities\ControllerUtilities;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DateTime;

class RefreshItemsAction
{
    /**
     * @var ControllerUtilities
     */  
    protected $utils;
    
    /**
     * @var ItemService
     */
    protected $service;

    /**
     * @param ControllerUtilities $utils
     * @param ItemService         $service
     */
    public function __construct(
        ControllerUtilities $utils,
        ItemService $service
    ) {
        $this->utils = $utils;
        $this->service = $service;
    }

    /**
     * @param  Request $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $incomplete = $request->request->get('incomplete') == '1';
        if ($incomplete) {
            $items = $this->service->getIncompleteItems();
        }
        else {
            $items = $this->service->getCompleteItems();
        }

        return $this->utils->render('ToDoListBundle::items_list.html.twig', array(
            'items' => $items,
            'incomplete' => $incomplete,
            'today' => (new DateTime())->format('Y-m-d')
        ));
    }
}
