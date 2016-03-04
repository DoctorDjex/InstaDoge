<?php

namespace Contest\ContestBundle\Controller;

use Contest\ContestBundle\Entity\Contest;
use Contest\ContestBundle\Entity\Image;
use Contest\ContestBundle\Helper\ContestHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ContestController extends Controller
{
    /**
     * @Route("/list", name="contest_list")
     * @Template()
     */
    public function listAction( Request $request )
    {
        $titrepage = '';
        $form = $this->createForm('contest_search_contest');
        $formView = $form->createView();
        if( $request->getMethod() == "POST" ){
            $form->handleRequest($request);
            $data = $form->getData();
            $contests = $this->getDoctrine()->getManager()->getRepository('ContestContestBundle:Contest')->findActivesByName($data['search']);
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $contests, /* query NOT result */
                $request->query->getInt('page', 1)/*page number*/,
                10/*limit per page*/
            );
            $titrepage = 'Résultats de la recherche pour "' . $data['search'] . '"';
        }else{
            $contests = $this->getDoctrine()->getManager()->getRepository('ContestContestBundle:Contest')->findActives();
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $contests, /* query NOT result */
                $request->query->getInt('page', 1)/*page number*/,
                10/*limit per page*/
            );
            $titrepage = 'Tous les concours';
        }
        return [
            'pagination' => $pagination,
            'form' => $formView,
            'titre' => $titrepage
        ];
    }

    /**
     * @Route("/details/{slug}", name="contest_details")
     * @Template()
     * @ParamConverter()
     */
    public function detailsAction(Contest $contest)
    {
        return [ 'contest' => $contest ];
    }

    /**
     * @Route("/gallery/{slug}", name="contest_gallery")
     * @Template()
     * @ParamConverter()
     */
    public function galleryAction(Contest $contest)
    {
        return [ 'contest' => $contest ];
    }

    /**
     * @Route("/create/contest", name="contest_create_contest")
     * @Template()
     */
    public function createAction(Request $request){
        $contest = new Contest();

        $form = $form = $this->createForm('contest_type', $contest);
        $viewVars = [];

        if( $request->getMethod() == "POST" ){
            $handler = $this->get("contest.form.handler.contest");

            if( $handler->process( $form, $contest ) ){
                $viewVars['error'] = false;
            } else {
                $viewVars['error'] = true;
            }
        }

        $viewVars[ "form" ] = $form->createView();

        return $viewVars;
    }

    /**
     * @Route("/winner/{slug}", name="contest_winner")
     * @Template()
     */
    public function winnerAction(Contest $contest){
        $viewVars = ['contest' => $contest];

        if( $winnerImage = $contest->getWinner() ){
            $viewVars['winnerImage'] = $winnerImage;
        }

        return $viewVars;
    }

    /**
     * @Route("/view-winner", name="contest_view_winner")
     * @Template()
     */
    public function viewWinnerAction(){
        $user = $this->getUser();
        $contests = $this->getDoctrine()->getManager()->getRepository('ContestContestBundle:Contest')->findActivesByOwner($user->getId());
        return [ 'contests' => $contests ];
    }

    /**
     * @Route("/view-winner/{slug}", name="contest_view_winner_detail")
     * @Template()
     */
    public function viewWinnerDetailAction(Contest $contest){
        $this->denyAccessUnlessGranted('view', $contest);
        $over = $contest->isOver();
        $winners = $contest->getWinners();
        return [ 'contest' => $contest , 'over' => $over , 'winners' => $winners ];
    }

    /**
     * @Route("/set-winner/{contest_slug}/{image_id}", name="contest_set_winner", requirements={"image_id"="\d+"})
     * @Template()
     * @ParamConverter("contest", class="ContestContestBundle:Contest", options={"mapping": {"contest_slug" = "slug"}})
     * @ParamConverter("image", class="ContestContestBundle:Image", options={"mappings": {"image_id" = "id"}})
     */
    public function setWinnerAction( Contest $contest, Image $image){
        $this->denyAccessUnlessGranted('view', $contest);
        $setimage = false;
        if( $contest->canSetWinner($image) ){
            $setimage = true;
            $contest->setWinner($image);
            $em = $this->getDoctrine()->getManager();
            $em->persist( $contest );
            $em->flush();
            return $this->redirect( $this->generateUrl('contest_winner', [ 'slug' => $contest->getSlug() ] ) );
        }
        return [ 'setimage' => $setimage ];
    }
}
