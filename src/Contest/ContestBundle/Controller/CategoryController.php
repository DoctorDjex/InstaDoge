<?php

namespace Contest\ContestBundle\Controller;

use Contest\ContestBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/category")
 */
class CategoryController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/lists", name="categorie_index")
     * @Template
     */
    public function listAction(Request $request)
    {
        $contests = $this->getDoctrine()->getManager()->getRepository('ContestContestBundle:Category')->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $contests, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );
        return [ 'pagination' => $pagination ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{id}", name="categorie_femme_index")
     * @Template
     * @ParamConverter()
     */
    public function listByCategoryAction(Category $category,Request $request)
    {
        $contests=$category->getContests();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $contests, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );
        return [ 'pagination' => $pagination ];
    }



}
