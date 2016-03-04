<?php

namespace Contest\ContestBundle\Tests\Controller;

class ContestControllerTest extends BaseWebTestController {

    public function testList_Success() {
        $this->client->request( 'GET', '/list' );
        $this->assertEquals( 'Contest\ContestBundle\Controller\ContestController::listAction', $this->client->getRequest()->attributes->get( '_controller' ) );
        $this->assertTrue( 200 === $this->client->getResponse()->getStatusCode() );

        $this->assertContains( 'Titre n°1', $this->client->getResponse()->getContent() );
    }

    public function testDetails_Redirect_Success() {
        $contest = $this->client->getContainer()->get( "doctrine.orm.entity_manager" )
            ->getRepository( 'ContestContestBundle:Contest' )
            ->findBy( [ 'title' => "titre n°1" ] )[0];

        $url = '/details/' . $contest->getSlug();

        $this->client->request( 'GET', $url );

        // Sans login, impossible d'accéder à la page donc redirection vers la page de login
        $this->assertTrue( 302 === $this->client->getResponse()->getStatusCode() );
        $this->assertRegExp( '/\/login$/', $this->client->getResponse()->headers->get( 'location' ) );
    }

    public function testDetails_Success() {
        $this->logIn();

        $contest = $this->client->getContainer()->get( "doctrine.orm.entity_manager" )
            ->getRepository( 'ContestContestBundle:Contest' )
            ->findBy( [ 'title' => "titre n°1" ] )[0];

        $url = '/details/' . $contest->getSlug();

        $this->client->request( 'GET', $url );

        // On se connecte à la page quand loggé
        $this->assertTrue( 200 === $this->client->getResponse()->getStatusCode() );
        $this->assertContains( 'Titre n°1', $this->client->getResponse()->getContent() );
    }

    public function testCreate_Fail_DateInversed() {
        $title = sha1( time() );

        $crawler = $this->client->request( 'GET', '/create/contest' );

        $form = $crawler->selectButton( "Créer mon concours" )->form(
            [ "contest_type[title]"           => "Titre : " . $title,
              "contest_type[beginDate][date]" => "2016-03-01 12:10:00",
              "contest_type[endDate][date]"   => "2016-02-29 00:00:00" ]
        );

        $crawler = $this->client->submit( $form );
        $response = $this->client->getResponse();

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertEquals( 1, $crawler->filter( "#error" )->count() );
        $this->assertContains( 'La date de début doit être inférieure à la date de fin', $response->getContent() );
    }

    public function testCreate_Fail_DateFormat() {
        $title = sha1( time() );

        $crawler = $this->client->request( 'GET', '/create/contest' );

        $form = $crawler->selectButton( "Créer mon concours" )->form(
            [ "contest_type[title]"           => "Titre : " . $title,
              "contest_type[beginDate][date]" => "2016-03-01",
              "contest_type[endDate][date]"   => "azer" ]
        );

        $crawler = $this->client->submit( $form );
        $response = $this->client->getResponse();

        // &#039; = '
        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertEquals( 1, $crawler->filter( "#error" )->count() );
        $this->assertContains( 'Cette valeur n&#039;est pas valide.', $response->getContent() );
    }

    public function testCreate_Fail_FormNotComplete() {
        $getCrawler = $this->client->request( 'GET', '/create/contest' );

        $form = $getCrawler->selectButton( "Créer mon concours" )->form();
        $this->client->submit( $form );
        $response = $this->client->getResponse();

        $this->assertEquals( 500, $response->getStatusCode() );

        // New test
        $title = sha1( time() );
        $form = $getCrawler->selectButton( "Créer mon concours" )->form(
            [ "contest_type[title]"         => "Titre : " . $title,
              "contest_type[endDate][date]" => "azer" ]
        );

        $this->client->submit( $form );
        $response = $this->client->getResponse();

        $this->assertEquals( 500, $response->getStatusCode() );

        // New test
        $form = $getCrawler->selectButton( "Créer mon concours" )->form(
            [ "contest_type[title]"           => "Titre : " . $title,
              "contest_type[beginDate][date]" => "2016-03-01", ]
        );

        $this->client->submit( $form );
        $response = $this->client->getResponse();

        $this->assertEquals( 500, $response->getStatusCode() );

        // New test
        $form = $getCrawler->selectButton( "Créer mon concours" )->form(
            [ "contest_type[beginDate][date]" => "2016-03-01", ]
        );

        $this->client->submit( $form );
        $response = $this->client->getResponse();

        $this->assertEquals( 500, $response->getStatusCode() );

        // New test
        $form = $getCrawler->selectButton( "Créer mon concours" )->form(
            [ "contest_type[endDate][date]" => "azer" ]
        );

        $this->client->submit( $form );
        $response = $this->client->getResponse();

        $this->assertEquals( 500, $response->getStatusCode() );
    }

    public function testCreate_Success() {
        $title = sha1( time() );
        $this->logIn();

        $crawler = $this->client->request( 'GET', '/create/contest' );

        $form = $crawler->selectButton( "Créer mon concours" )->form(
            [ "contest_type[title]"           => "Titre : " . $title,
              "contest_type[beginDate][date]" => "2016-03-01 10:00:00",
              "contest_type[endDate][date]"   => "2016-03-10 10:00:00" ]
        );

        $this->client->submit( $form );
        $response = $this->client->getResponse();

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( 'Le concours a été créé !', $response->getContent() );

        $contest = $this->om->getRepository("ContestContestBundle:Contest")->findBy(['title' => "Titre : " . $title ])[0];
        $this->assertNotNull($contest->getOwner());
        $this->assertEquals( "Titre : " . $title, $contest->getTitle() );
    }
}
