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

        $crawler = $this->client->request( 'GET', '/' . $contest->getSlug() . '/upload/image' );

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

        $image = $this->om->getRepository( "ContestContestBundle:Image" )->findBy( [ 'title' => "Titre image : " . self::$title ] )[0];

        $user = $this->client->getContainer()->get( "security.token_storage" )->getToken()->getUser();
        $this->assertEquals( $image->getOwner()->getId(), $user->getId() );
        $this->assertEquals( $image->getContest()->getId(), $contest->getId() );
        $this->assertTrue( file_exists( $image->getAbsolutePath() ) );

        $this->imageId = $image->getId();
        $this->om->clear();
    }

    public function testVote_Success() {
        $response = $this->sendVote();

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Merci d'avoir voté !", $response->getContent() );

        //$image = $this->om->getRepository( "ContestContestBundle:Image" )->findBy( [ 'title' => "Titre image : " . self::$title ] )[0];

        $this->assertEquals( 1, count( $image->getVotes() ) );
    }

    public function testVote_Fail_VoteExists() {
        $response = $this->sendVote();

        $this->assertEquals( 200, $response->getStatusCode() );
        $this->assertContains( "Vous avez déjà voté pour cette photo !", $response->getContent() );

        //$image = $this->om->getRepository( "ContestContestBundle:Image" )->findBy( [ 'title' => "Titre image : " . self::$title ] )[0];

        $this->assertEquals( 1, count( $image->getVotes() ) );
    }

    protected function sendVote() {
        $this->logIn();

        $url = $this->client->getContainer()->get( "router" )->generate( "contest_image_vote", [
            'id' => $this->om->getRepository( "ContestContestBundle:Image" )
                ->findBy( [ 'title' => "Titre image : " . self::$title ] )[0]
                ->getId()
        ] );

        $this->client->request( 'GET', $url );

        return $this->client->getResponse();
    }

}
