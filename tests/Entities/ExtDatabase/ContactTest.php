<?php

namespace App\Tests\Entities\ExtDatabase;

use App\Entity\ExtDatabase\Contact;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ContactTest extends KernelTestCase
{
    /** ------------------------------- TESTS ------------------------------- */
    public function testValidEntity(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidEntity_ifFirstnameEmpty(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setFirstname(''), 2);
    }

    public function testInvalidEntity_ifFirstnameNull(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setFirstname(null), 1);
    }

    public function testInvalidEntity_ifFirstnameLessThanMinLength(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setFirstname('Q'), 1);
    }

    public function testInvalidEntity_ifLastnameEmpty(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setLastname(''), 2);
    }

    public function testInvalidEntity_ifLastnameNull(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setLastname(null), 1);
    }

    public function testInvalidEntity_ifLastnameLessThanMinLength(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setLastname('G'), 1);
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

    public function testInvalidEntity_ifEmailNull(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setEmail(null), 1);
    }

    public function testInvalidEntity_ifMessageEmpty(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setMessage(''), 2);
    }

    public function testInvalidEntity_ifMessageNull(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setMessage(null), 1);
    }

    public function testInvalidEntity_ifMessageLessThanMinLength(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setMessage('123456789'), 1);
    }

    public function testGettersForEntity(): void
    {
        self::bootKernel();
        $entity = $this->getEntity();
        $this->assertEquals('QGT', $entity->getLastname());
        $this->assertEquals('Quentin', $entity->getFirstname());
        $this->assertEquals('lorem ipsum', $entity->getMessage());
        $this->assertEquals('email@domain.com', $entity->getEmail());
    }

    /** ------------------------------- METHODS ------------------------------- */

    /**
     * @param int|null $number
     */
    private function assertHasErrors(Contact $contact, $number = null): void
    {
        /* @phpstan-ignore-next-line */
        $errors = self::$container->get('validator')->validate($contact);
        if (null === $number) {
            $this->assertTrue(count($errors) > 0);
        } else {
            $this->assertCount($number, $errors);
        }
    }

    private function getEntity(): Contact
    {
        $contact = (new Contact())
            ->setFirstname('Quentin')
            ->setLastname('QGT')
            ->setEmail('email@domain.com')
            ->setMessage('lorem ipsum');

        return $contact;
    }
}
