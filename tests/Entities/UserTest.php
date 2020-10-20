<?php

namespace App\Tests\Entities;

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
