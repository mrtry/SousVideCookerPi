<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CookingController extends Controller
{
    /**
     * @Route("/start")
     */
    public function startAction()
    {
        return $this->render('AppBundle:Cooking:start.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/stop")
     */
    public function stopAction()
    {
        return $this->render('AppBundle:Cooking:stop.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/status")
     */
    public function statusAction()
    {
        return $this->render('AppBundle:Cooking:status.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/changeTemperature")
     */
    public function changeTemperatureAction()
    {
        return $this->render('AppBundle:Cooking:change_temperature.html.twig', array(
            // ...
        ));
    }

}
