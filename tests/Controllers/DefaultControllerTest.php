<?php

namespace App\Tests\Controllers;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class DefaultControllerTest extends WebTestCase
{
    /** ------------------------------- TESTS ------------------------------- */

    /**
     * @dataProvider provideUrlsName
     *
     * @param mixed $urlName
     */
    public function testPageIsSuccessful($urlName): void
    {
        $client = static::createClient();

        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');

        $crawler = $client->request(Request::METHOD_GET, $router->generate($urlName));

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertGreaterThan(0, $crawler->filter('h1')->count());
        $this->assertCount(1, $crawler->filter('.fixed.z-50'));
        $this->assertCount(1, $crawler->filter('nav'));
        /* @phpstan-ignore-next-line */
        $this->assertStringContainsString('GitHub', $client->getResponse()->getContent());
        /* @phpstan-ignore-next-line */
        $this->assertStringContainsString('Twitter', $client->getResponse()->getContent());
    }

    /** ------------------------------- METHODS ------------------------------- */

    /**
     * @return array<mixed>
     */
    public function provideUrlsName(): array
    {
        return [
            ['app_homepage'],
            ['app_portfolio'],
            ['app_contact'],
        ];
    }
}
