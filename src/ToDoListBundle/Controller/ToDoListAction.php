<?php

namespace ToDoListBundle\Controller;

use ToDoListBundle\Service\ItemService;
use ToDoListBundle\Utilities\ControllerUtilities;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DateTime;

class ToDoListAction
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
        return $this->utils->render('ToDoListBundle::to_do_list.html.twig', array(
            'items' => $this->service->getIncompleteItems(),
            'pageSubTitle' => 'Items To Do',
            'incomplete' => true,
            'today' => (new DateTime())->format('Y-m-d')
        ));
    }
}
