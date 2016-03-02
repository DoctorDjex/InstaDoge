<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/log/{nom}", name="homepage")
     */
    public function indexAction(Request $request, $nom)
    {
        $logger = $this->get('logger');
        $logger->info('Nom : ' . $nom);

        return new Response("J'ai écrit " . $nom . " dans le log");
    }
}
