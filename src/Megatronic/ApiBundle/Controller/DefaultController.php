<?php

namespace MegatronicApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MegatronicApiBundle:Default:index.html.twig');
    }
}
