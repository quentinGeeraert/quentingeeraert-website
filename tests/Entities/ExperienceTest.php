<?php

namespace App\Tests\Entities;

use App\Entity\Experience;
use App\Entity\User;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ExperienceTest extends KernelTestCase
{
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

    public function testValidEntity_ifEmploymentTypeEmpty(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setEmploymentType(''), 0);
    }

    public function testInvalidEntity_ifCompanyEmpty(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setCompany(''), 1);
    }

    public function testInvalidEntity_ifDescriptionEmpty(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setDescription(''), 1);
    }

    public function testInvalidEntity_ifLogoEmpty(): void
    {
        self::bootKernel();
        $this->assertHasErrors($this->getEntity()->setLogo(''), 1);
    }

    /** ------------------------------- METHODS ------------------------------- */

    /**
     * @param int|null $number
     */
    private function assertHasErrors(Experience $experience, $number = null): void
    {
        /* @phpstan-ignore-next-line */
        $errors = self::$container->get('validator')->validate($experience);
        if (null === $number) {
            $this->assertTrue(count($errors) > 0);
        } else {
            $this->assertCount($number, $errors);
        }
    }

    private function getEntity(): Experience
    {
        $experience = (new Experience())
            ->setUser(new User())
            ->setStartDate(new DateTime())
            ->setEndDate(null)
            ->setTitle('title')
            ->setEmploymentType('employment')
            ->setCompany('company')
            ->setLocation('Lille, France')
            ->setDescription('lorem ipsum')
            ->setLogo('logo.png');

        return $experience;
    }
}
