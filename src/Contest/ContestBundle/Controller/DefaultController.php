<?php

namespace Contest\ContestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/", name="contest_index")
     * @Template
     */
    public function indexAction()
    {
        $contests = $this->getDoctrine()->getManager()->getRepository('ContestContestBundle:Contest')->findFinished();

        return [ 'contests' => $contests ];
    }
}
