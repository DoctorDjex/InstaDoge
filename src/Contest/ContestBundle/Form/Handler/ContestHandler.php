<?php

namespace Contest\ContestBundle\Form\Handler;

use Contest\ContestBundle\Entity\Contest;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 02/03/2016
 * Time: 10:17.
 */
class ContestHandler
{
    protected $request;
    protected $em;

    public function __construct(Request $request, EntityManager $em)
    {
        $this->request = $request;
        $this->em = $em;
    }

    public function process(FormInterface $form, Contest $contest)
    {
        $form->setData($contest);

        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $this->onSuccess($contest);

            return true;
        }

        return false;
    }

    protected function onSuccess(Contest $contest)
    {
        $this->em->persist($contest);
        $this->em->flush();
    }
}
