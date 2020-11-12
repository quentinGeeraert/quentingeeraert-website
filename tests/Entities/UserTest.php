<?php

namespace App\Tests\Entities;

use App\Entity\Skill;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    /** ------------------------------- TESTS ------------------------------- */
    public function testValidEntity(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidEntity_ifEmailFails(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setEmail('emaildomain.com'), 1);
    }

    public function testInvalidEntity_ifEmailEmpty(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setEmail(''), 1);
    }

    public function testValidEntity_ifRolesEmpty(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setRoles([]), 0);
    }

    public function testInvalidEntity_ifPasswordEmpty(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setPassword(''), 1);
    }

    public function testGettersForEntity(): void
    {
        self::bootKernel();
        $entity = $this->getEntity();

        $this->assertEquals(null, $entity->getId());

        $email = 'email@domain.com';

        $this->assertEquals($email, $entity->getEmail());
        $this->assertEquals($email, $entity->getUsername());
        $this->assertEquals(['ROLE_USER'], $entity->getRoles());
        $this->assertNotEmpty($entity->getPassword());
        $this->assertEmpty($entity->getSkills());

        /* @phpstan-ignore-next-line */
        $this->assertNull($entity->eraseCredentials());
        $this->assertEquals($email, $entity->__toString());
    }

    public function testSkillMethods(): void
    {
        self::bootKernel();
        $entity = $this->getEntity();
        $skill = new Skill();

        $entity->addSkill($skill);
        $this->assertContains($skill, $entity->getSkills());
        $this->assertNotEmpty($entity->getSkills());

        $entity->removeSkill($skill);
        $this->assertEmpty($entity->getSkills());
    }

    /** ------------------------------- METHODS ------------------------------- */

    /**
     * @param int|null $number
     */
    private function assertHasErrors(User $user, $number = null): void
    {
        /* @phpstan-ignore-next-line */
        $errors = self::$container->get('validator')->validate($user);
        if (null === $number) {
            $this->assertTrue(count($errors) > 0);
        } else {
            $this->assertCount($number, $errors);
        }
    }

    private function getEntity(): User
    {
        $user = new User();
        $encoder = self::$container->get('security.password_encoder');
        /* @phpstan-ignore-next-line */
        $encoded = $encoder->encodePassword($user, 'password');
        $user = (new User())->setEmail('email@domain.com')->setRoles(['ROLE_USER'])->setPassword($encoded);

        return $user;
    }
}
