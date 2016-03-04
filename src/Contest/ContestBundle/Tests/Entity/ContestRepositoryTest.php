<?php

namespace Contest\ContestBundle\Tests\Entity;

use Contest\ContestBundle\Entity\Contest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContestRepositoryTest extends WebTestCase{
    private $repo;

    private $em;

    public function setUp() {
        $this->em = $this->createClient()
            ->getContainer()
            ->get('doctrine.orm.entity_manager');
        $this->repo = $this->em
            ->getRepository('ContestContestBundle:Contest')
        ;
    }

    public function testFindActives_Success(){
        $actives = $this->repo->findActivesQb()->getQuery()->getResult();

        $this->assertEquals( count( $actives ), 1 );
    }

    public function testFindActives_Fail(){
        $actives = $this->repo->findFinished();

        $this->assertEquals( count( $actives ), 1 );
    }
}