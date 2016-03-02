<?php

namespace Contest\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ContestAdminBundle:Default:index.html.twig', array('name' => $name));
    }
}
