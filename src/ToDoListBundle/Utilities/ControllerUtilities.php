<?php

namespace ToDoListBundle\Utilities;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

class ControllerUtilities
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @param EngineInterface $templating
     */
    public function __construct(
        EngineInterface $templating
    ) {
        $this->templating = $templating;
    }

    /**
     * @param  string        $view 
     * @param  array         $parameter
     * @return Response
     */
    public function render($view, $parameters = [])
    {
        $response = new Response();
        $response->setContent($this->templating->render($view, $parameters));
        return $response;
    }
}