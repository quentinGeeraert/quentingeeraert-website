<?php

namespace App\Tests\Controllers;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\ProjectPortfolio;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    /** @var mixed */
    private $entityManager;

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

    /** ------------------------------- ARTICLES ------------------------------- */
    public function testArticlePageNotFound(): void
    {
        $client = static::createClient();

        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');

        $client->request(Request::METHOD_GET, $router->generate('app_articles', ['slug' => 'not-found-article']));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testArticlePageOffline(): void
    {
        $client = static::createClient();

        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');

        $article = (new Article())
            ->setTitle('Article')
            ->setSlug('article-slug')
            ->setDescription('lorem ipsum')
            ->setPictures([])
            ->setIsOnline(false)
            ->setHtmlContent(null);

        $this->insertEntityInDatabase($article);

        $client->request(Request::METHOD_GET, $router->generate('app_articles', ['slug' => $article->getSlug()]));
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testArticlePageOnline(): void
    {
        $client = static::createClient();

        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');

        $article = (new Article())
            ->setTitle('Article')
            ->setSlug('article-slug-on')
            ->setDescription('lorem ipsum')
            ->setPictures([])
            ->setIsOnline(true)
            ->setHtmlContent(null);

        $this->insertEntityInDatabase($article);

        $client->request(Request::METHOD_GET, $router->generate('app_articles', ['slug' => $article->getSlug()]));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /** ------------------------------- SKILLS ------------------------------- */
    public function testSkillUser(): void
    {
        $client = static::createClient();

        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');

        $user = new User();
        $encoder = self::$container->get('security.password_encoder');
        /* @phpstan-ignore-next-line */
        $encoded = $encoder->encodePassword($user, 'password');
        $user = (new User())->setEmail('email@domain.com')->setRoles(['ROLE_USER'])->setPassword($encoded);

        $this->insertEntityInDatabase($user);

        $client->request(Request::METHOD_GET, $router->generate('app_skills'));

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /** ------------------------------- PORTFOLIO ------------------------------- */
    public function testPortfolioPageNotFound(): void
    {
        $client = static::createClient();

        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');

        $client->request(Request::METHOD_GET, $router->generate('app_portfolio', ['slug' => 'not-found-portfolio']));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testPortfolioPageOffline(): void
    {
        $client = static::createClient();

        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');

        $projectPortfolio = (new ProjectPortfolio())
            ->setName('ProjectPortfolio')
            ->setSlug('project-portfolio')
            ->setDescription('lorem ipsum')
            ->setPictures(['image1.png', 'image2.png'])
            ->setCategories(['cat1', 'cat2', 'cat3'])
            ->setIsOnline(false)
            ->setHtmlContent(null);

        $this->insertEntityInDatabase($projectPortfolio);

        $client->request(Request::METHOD_GET, $router->generate('app_portfolio', ['slug' => $projectPortfolio->getSlug()]));
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testPortfolioPageOnline(): void
    {
        $client = static::createClient();

        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');

        $projectPortfolio = (new ProjectPortfolio())
            ->setName('ProjectPortfolio')
            ->setSlug('project-portfolio-on')
            ->setDescription('lorem ipsum')
            ->setPictures(['image1.png', 'image2.png'])
            ->setCategories(['cat1', 'cat2', 'cat3'])
            ->setIsOnline(true)
            ->setHtmlContent(null);

        $kernel = self::bootKernel();

        $this->insertEntityInDatabase($projectPortfolio);

        $client->request(Request::METHOD_GET, $router->generate('app_portfolio', ['slug' => $projectPortfolio->getSlug()]));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /** ------------------------------- CONTACT ------------------------------- */
    public function testContactPage(): void
    {
        $client = self::createClient();
        $client->request('GET', '/contact');
        $client->submitForm('Send', [
            'contact[firstname]' => 'Quentin',
            'contact[lastname]' => 'QGT',
            'contact[email]' => 'email@domain.com',
            'contact[message]' => 'This is a test message',
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('.shadow-md.bg-blue-100', 'Your request has been sent');
    }

    /** ------------------------------- METHODS ------------------------------- */

    /**
     * @return array<mixed>
     */
    public function provideUrlsName(): array
    {
        return [
            ['app_homepage'],
            ['app_articles'],
            ['app_experiences'],
            ['app_skills'],
            ['app_portfolio'],
            ['app_contact'],
        ];
    }

    /**
     * @param mixed $entity
     */
    public function insertEntityInDatabase($entity): void
    {
        $kernel = self::bootKernel();

        /* @phpstan-ignore-next-line */
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
