<?php

namespace App\Tests\Entities\traits;

trait TimestampsTestTrait
{
    public function testTimestampsBeforePersist(): void
    {
        self::bootKernel();
        $entity = $this->getEntity();

        $this->assertEquals(null, $entity->getCreatedAt());
        $this->assertEquals(null, $entity->getUpdatedAt());
    }

    public function testOnlyCreatedAtCanBeUpdateIfNull(): void
    {
        self::bootKernel();
        $entity = $this->getEntity();
        $entity->updatedTimestamps();

        $this->assertNotEquals(null, $entity->getCreatedAt());
        $this->assertEquals(null, $entity->getUpdatedAt());
    }

    public function testUpdatedAtUpdateIfCreatedAtNotNull(): void
    {
        self::bootKernel();
        $entity = $this->getEntity();

        $createdAt = (new \Datetime('2020-11-11 12:45:13'));
        $entity->setCreatedAt($createdAt);
        $entity->updatedTimestamps();

        $this->assertEquals($createdAt, $entity->getCreatedAt());
        $this->assertNotEquals(null, $entity->getUpdatedAt());
    }
}
