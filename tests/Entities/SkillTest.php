<?php

namespace App\Tests\Entities;

use App\Entity\Skill;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SkillTest extends KernelTestCase
{
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

    public function testValidEntity_ifLogoEmpty(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setLogo(''), 0);
    }

    public function testValidEntity_ifLogoNull(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setLogo(null), 0);
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

    public function testGettersForEntity(): void
    {
        self::bootKernel();
        $entity = $this->getEntity();

        $this->assertEmpty($entity->getUsers());
        $this->assertEquals(null, $entity->getId());
        $this->assertEquals('PHP', $entity->getName());
        $this->assertEquals('image.png', $entity->getLogo());
        $this->assertEquals(null, $entity->getDescription());
    }

    public function testUserMethods(): void
    {
        self::bootKernel();
        $entity = $this->getEntity();
        $user = new User();

        $entity->addUser($user);
        $this->assertContains($user, $entity->getUsers());
        $this->assertNotEmpty($entity->getUsers());

        $entity->removeUser($user);
        $this->assertEmpty($entity->getUsers());
    }

    /** ------------------------------- METHODS ------------------------------- */

    /**
     * @param int|null $number
     */
    private function assertHasErrors(Skill $skill, $number = null): void
    {
        /* @phpstan-ignore-next-line */
        $errors = self::$container->get('validator')->validate($skill);
        if (null === $number) {
            $this->assertTrue(count($errors) > 0);
        } else {
            $this->assertCount($number, $errors);
        }
    }

    private function getEntity(): Skill
    {
        $skill = (new Skill())
            ->setName('PHP')
            ->setLogo('image.png')
            ->setDescription(null);

        return $skill;
    }
}
