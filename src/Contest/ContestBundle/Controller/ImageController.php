<?php

namespace Contest\ContestBundle\Controller;

use Contest\ContestBundle\Entity\Contest;
use Contest\ContestBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class ImageController extends Controller
{
    /**
     * @Route("/{slug}/upload/image", name="contest_image_upload")
     * @Template()
     * @ParamConverter()
     */
    public function uploadImageAction(Request $request, Contest $contest) {
        $image = new Image();
        $form = $this->createForm('image_type', $image);

        $viewVars = [ "contest" => $contest ];

        if( $request->getMethod() == "POST" ){
            $form->handleRequest( $request );

            if( $form->isValid() ){
                $image->setContest( $contest );
                $em = $this->getDoctrine()->getManager();
                $em->persist( $image );
                $em->flush();

                $viewVars['error'] = false;
            } else {
                $viewVars['error'] = true;
            }
        }

        $viewVars['form'] = $form->createView();
        return $viewVars;
    }

    /**
     * @param Image   $image
     *
     * @return array
     *
     * @Route("/vote/{id}", name="contest_image_vote")
     * @ParamConverter()
     * @Template()
     */
    public function voteAction(Image $image){
        $viewVars = [ 'image' => $image ];

        if( !$this->getUser()->hadAlreadyVoted( $image ) ){
            $image->addVote( $this->getUser() );

            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush();
        } else {
            $viewVars['error'] = true;
        }

        return $viewVars;
    }

}
