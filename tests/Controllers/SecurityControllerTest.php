<?php

namespace App\Tests\Controllers;

use App\Entity\User;
use Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class SecurityControllerTest extends WebTestCase
{
    /** @var mixed */
    private $entityManager;

    /** ------------------------------- TESTS ------------------------------- */
    public function testSuccessful(): void
    {
        $client = static::createClient();

        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');

        $crawler = $client->request(Request::METHOD_GET, $router->generate('app_login'));

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $user = new User();
        $encoder = self::$container->get('security.password_encoder');
        /* @phpstan-ignore-next-line */
        $encoded = $encoder->encodePassword($user, 'password');
        $user = (new User())->setEmail('email@domain.com')->setRoles(['ROLE_ADMIN'])->setPassword($encoded);
        $this->insertEntityInDatabase($user);

        $form = $crawler->filter('form')->form([
            'username_field' => 'email@domain.com',
            'password_field' => 'password',
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertRouteSame('bo_dashboard');

        $client->request(Request::METHOD_GET, $router->generate('app_logout'));

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertRouteSame('app_homepage');
    }

    public function testFailedIfRoleIsNotAdmin(): void
    {
        $client = static::createClient();

        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');

        $crawler = $client->request(Request::METHOD_GET, $router->generate('app_login'));

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $user = new User();
        $encoder = self::$container->get('security.password_encoder');
        /* @phpstan-ignore-next-line */
        $encoded = $encoder->encodePassword($user, 'password');
        $user = (new User())->setEmail('email@domain.com')->setRoles(['ROLE_USER'])->setPassword($encoded);
        $this->insertEntityInDatabase($user);

        $form = $crawler->filter('form')->form([
            'username_field' => 'email@domain.com',
            'password_field' => 'password',
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @dataProvider provideFailed
     *
     * @param array<string,string> $formData
     */
    public function testFailed(array $formData, string $errorMessage): void
    {
        $client = static::createClient();

        /** @var RouterInterface $router */
        $router = $client->getContainer()->get('router');

        $crawler = $client->request(Request::METHOD_GET, $router->generate('app_login'));

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $form = $crawler->filter('form')->form($formData);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertSelectorTextContains('html', $errorMessage);
    }

    /** ------------------------------- METHODS ------------------------------- */

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

    /**
     * @return Generator<mixed>
     */
    public function provideFailed(): Generator
    {
        yield [
            [
                'username_field' => 'email@domain.com',
                'password_field' => 'fail',
                '_csrf_token' => '',
            ],
            'Invalid CSRF token.',
        ];

        yield [
            [
                'username_field' => 'email@domain.com',
                'password_field' => 'fail',
            ],
            'Email could not be found.',
        ];

        yield [
            [
                'username_field' => 'fail',
                'password_field' => 'fail',
            ],
            'Email could not be found.',
        ];

        yield [
            [
                'username_field' => '',
                'password_field' => '',
            ],
            'Email could not be found.',
        ];
    }
}
