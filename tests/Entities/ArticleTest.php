<?php

namespace App\Tests\Entities;

use App\Entity\Article;
use App\Tests\Entities\traits\TimestampsTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ArticleTest extends KernelTestCase
{
    use TimestampsTestTrait;

    /** ------------------------------- TESTS ------------------------------- */
    public function testValidEntity(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidEntity_ifTitleEmpty(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setTitle(''), 1);
    }

    public function testInvalidEntity_ifSlugEmpty(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setSlug(''), 1);
    }

    public function testValidEntity_ifDescriptionEmpty(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setDescription(''), 0);
    }

    public function testValidEntity_ifDescriptionNull(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setDescription(null), 0);
    }

    public function testValidEntity_ifHtmlContentEmpty(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setHtmlContent(''), 0);
    }

    public function testValidEntity_ifHtmlContentNull(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setHtmlContent(null), 0);
    }

    public function testGettersForEntity(): void
    {
        self::bootKernel();
        $entity = $this->getEntity();

        $this->assertEquals(null, $entity->getId());
        $this->assertEquals('Article', $entity->getTitle());
        $this->assertEquals('article-slug', $entity->getSlug());
        $this->assertEquals('lorem ipsum', $entity->getDescription());
        $this->assertEquals([], $entity->getPictures());
        $this->assertEquals(false, $entity->getIsOnline());
        $this->assertEquals(null, $entity->getHtmlContent());
    }

    /** ------------------------------- METHODS ------------------------------- */

    /**
     * @param int|null $number
     */
    private function assertHasErrors(Article $article, $number = null): void
    {
        /* @phpstan-ignore-next-line */
        $errors = self::$container->get('validator')->validate($article);
        if (null === $number) {
            $this->assertTrue(count($errors) > 0);
        } else {
            $this->assertCount($number, $errors);
        }
    }

    private function getEntity(): Article
    {
        $article = (new Article())
            ->setTitle('Article')
            ->setSlug('article-slug')
            ->setDescription('lorem ipsum')
            ->setPictures([])
            ->setIsOnline(false)
            ->setHtmlContent(null);

        return $article;
    }
}
