<?php

namespace Kod3r\LogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('Kod3rLogBundle:Default:index.html.twig', array('name' => $name));
    }
}
