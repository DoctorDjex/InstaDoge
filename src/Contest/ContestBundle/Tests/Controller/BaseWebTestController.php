<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 04/03/2016
 * Time: 09:21
 */

namespace Contest\ContestBundle\Tests\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class BaseWebTestController extends WebTestCase{
    /**
     * @var Client
     */
    protected $client = null;

    /**
     * @var ObjectManager $om
     */
    protected $om = null;

    public function setUp() {
        $this->client = static::createClient();
        $this->om = $this->client->getContainer()->get("doctrine.orm.entity_manager");
    }

    protected function logIn() {
        $session = $this->client->getContainer()->get( 'session' );
        $user = $this->om->getRepository("ContestUserBundle:User")->findBy(['username' => "TUser 1"])[0];

        $firewall = 'main';
        $token = new UsernamePasswordToken( $user, null, $firewall, [ 'ROLE_USER' ] );
        $session->set( '_security_' . $firewall, serialize( $token ) );
        $session->save();

        $cookie = new Cookie( $session->getName(), $session->getId() );
        $this->client->getCookieJar()->set( $cookie );
    }

    protected function checkRedirectedToLogin(){
        // Sans login, impossible d'accéder à la page donc redirection vers la page de login
        $this->assertTrue( 302 === $this->client->getResponse()->getStatusCode() );
        $this->assertRegExp( '/\/login$/', $this->client->getResponse()->headers->get( 'location' ) );
    }
}