<?php

namespace Frcho\Bundle\CrontaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('FrchoCrontaskBundle:Default:index.html.twig');
    }
}
