<?php

namespace Contest\ContestBundle\DataFixture\ORM;

use Contest\ContestBundle\Entity\Contest;
use Contest\ContestBundle\Entity\Image;
use Contest\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadContestFixture implements FixtureInterface{
    function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('TUser 1');
        $user->setEmail('tuser1@mail.com');
        $user->setPassword("toto");

        $manager->persist($user);

        $contest = new Contest();
        $contest->setTitle('Titre n°1');

        $beginDate = new \DateTime();
        $beginDate->modify('-3 days');
        $endDate = new \DateTime();
        $endDate->modify('+3 days');

        $contest->setBeginDate($beginDate);
        $contest->setEndDate($endDate);
        $contest->setOwner( $user );

        for( $i = 1; $i < 4; $i++ ){
            $image = new Image();
            $image->setTitle( "Title image contest 1 image " . $i );
            $image->setDescription( "Description image contest 1 image " . $i );
            $image->setPath("test/image" . $i . "jpeg");

            $contest->addImage( $image );
        }

        $manager->persist($contest);

        $contest = new Contest();
        $contest->setTitle('Titre n°2');

        $beginDate = new \DateTime();
        $beginDate->modify('+3 days');
        $endDate = new \DateTime();
        $endDate->modify('+6 days');

        $contest->setBeginDate($beginDate);
        $contest->setEndDate($endDate);
        $contest->setOwner( $user );

        for( $i = 1; $i < 4; $i++ ){
            $image = new Image();
            $image->setTitle( "Title image contest 2 image " . $i );
            $image->setDescription( "Description image contest 2 image " . $i );
            $image->setPath("test/image" . $i . "jpeg");

            $contest->addImage( $image );
        }

        $manager->persist($contest);

        $contest = new Contest();
        $contest->setTitle('Titre n°3');

        $beginDate = new \DateTime();
        $beginDate->modify('-6 days');
        $endDate = new \DateTime();
        $endDate->modify('-3 days');

        $contest->setBeginDate($beginDate);
        $contest->setEndDate($endDate);
        $contest->setOwner( $user );

        for( $i = 1; $i < 4; $i++ ){
            $image = new Image();
            $image->setTitle( "Title image contest 3 image " . $i );
            $image->setDescription( "Description image contest 3 image " . $i );
            $image->setPath("test/image" . $i . "jpeg");

            $contest->addImage( $image );
        }

        $manager->persist($contest);

        $manager->flush();
    }
}