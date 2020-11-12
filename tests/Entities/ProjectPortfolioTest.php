<?php

namespace App\Tests\Entities;

use App\Entity\ProjectPortfolio;
use App\Tests\Entities\traits\TimestampsTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProjectPortfolioTest extends KernelTestCase
{
    use TimestampsTestTrait;

    /** ------------------------------- TESTS ------------------------------- */
    public function testValidEntity(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidEntity_ifNameEmpty(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setName(''), 1);
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

    public function testValidEntity_ifPicturesEmpty(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setPictures([]), 0);
    }

    public function testValidEntity_ifCategoriesEmpty(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setCategories([]), 0);
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

        $this->assertEquals('ProjectPortfolio', $entity->getName());
        $this->assertEquals('project-portfolio', $entity->getSlug());

        $this->assertEquals('lorem ipsum', $entity->getDescription());
        $this->assertEquals(['image1.png', 'image2.png'], $entity->getPictures());
        $this->assertEquals(['cat1', 'cat2', 'cat3'], $entity->getCategories());

        $this->assertEquals(false, $entity->getIsOnline());
        $this->assertEquals(null, $entity->getHtmlContent());
    }

    /** ------------------------------- METHODS ------------------------------- */

    /**
     * @param int|null $number
     */
    private function assertHasErrors(ProjectPortfolio $projectPortfolio, $number = null): void
    {
        /* @phpstan-ignore-next-line */
        $errors = self::$container->get('validator')->validate($projectPortfolio);
        if (null === $number) {
            $this->assertTrue(count($errors) > 0);
        } else {
            $this->assertCount($number, $errors);
        }
    }

    private function getEntity(): ProjectPortfolio
    {
        $projectPortfolio = (new ProjectPortfolio())
            ->setName('ProjectPortfolio')
            ->setSlug('project-portfolio')
            ->setDescription('lorem ipsum')
            ->setPictures(['image1.png', 'image2.png'])
            ->setCategories(['cat1', 'cat2', 'cat3'])
            ->setIsOnline(false)
            ->setHtmlContent(null);

        return $projectPortfolio;
    }
}
