<?php

namespace Contest\ContestBundle\Tests\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageControllerTest extends BaseWebTestController {
    public static $title;

    public static function setUpBeforeClass() {
        self::$title = sha1( time() );
    }

    public function testUploadimage_Success() {
        $this->logIn();

        $contest = $this->om->getRepository( "ContestContestBundle:Contest" )->findBy( [ 'title' => "Titre n°1" ] )[0];
        $url = $this->client->getContainer()->get( "router" )->generate( "contest_image_upload", [
            'slug' => $contest->getSlug()
        ] );

        $crawler = $this->client->request( 'GET', $url );

        $photo = new UploadedFile(
            '/Users/Guillaume/Sites/cours_esgi_sf2/web/uploads/test/image1.jpeg',
            'image1.jpg',
            'image/jpeg',
            getimagesize( '/Users/Guillaume/Sites/cours_esgi_sf2/web/uploads/test/image1.jpeg' )
        );

        $form = $crawler->selectButton( "Ajouter" )->form(
            [ "image_type[title]"       => "Titre image : " . self::$title,
              "image_type[description]" => "Description image : " . self::$title,
              "image_type[file]"        => $photo
            ]
        );

        $this->client->submit( $form );
        $response = $this->client->getResponse();

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( 'L\'image a été envoyée', $response->getContent() );

        $image = $image = $this->getImage();

        $user = $this->client->getContainer()->get( "security.token_storage" )->getToken()->getUser();
        $this->assertEquals( $image->getOwner()->getId(), $user->getId() );
        $this->assertEquals( $image->getContest()->getId(), $contest->getId() );
        $this->assertTrue( file_exists( $image->getAbsolutePath() ) );

        $this->imageId = $image->getId();
        $this->om->clear();
    }

    public function testUploadImage_Fail_NotLoggedIn(){
        $contest = $this->om->getRepository( "ContestContestBundle:Contest" )->findBy( [ 'title' => "Titre n°1" ] )[0];

        $url = $this->client->getContainer()->get( "router" )->generate( "contest_image_upload", [
            'slug' => $contest->getId()
        ] );
        $crawler = $this->client->request( 'GET', $url );

        $this->checkRedirectedToLogin();
    }

    public function testVote_Fail_NotLoggedIn(){
        $response = $this->sendVote(false);

        $this->checkRedirectedToLogin();
    }

    public function testVote_Success() {
        $response = $this->sendVote();

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Merci d'avoir voté !", $response->getContent() );

        $image = $this->getImage();

        $this->assertEquals( 1, count( $image->getVotes() ) );
    }

    public function testVote_Fail_VoteExists() {
        $response = $this->sendVote();

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Vous avez déjà voté pour cette photo !", $response->getContent() );

        $image = $this->getImage();

        $this->assertEquals( 1, count( $image->getVotes() ) );
    }

    protected function sendVote( $withLogin = true ) {
        if( $withLogin ){
            $this->logIn();
        }

        $url = $this->client->getContainer()->get( "router" )->generate( "contest_image_vote", [
            'id' => $this->getImage()
                ->getId()
        ] );

        $this->client->request( 'GET', $url );

        return $this->client->getResponse();
    }

    protected function getImage(){
        return $this->om->getRepository( "ContestContestBundle:Image" )->findBy( [ 'title' => "Titre image : " . self::$title ] )[0];
    }
}
