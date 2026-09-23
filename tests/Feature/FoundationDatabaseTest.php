<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Risk;
use App\Models\Service;
use App\Models\User;
use Tests\TestCase;

class FoundationDatabaseTest extends TestCase
{
    public function test_required_foundation_models_and_fields_exist(): void
    {
        $this->assertTrue(class_exists(User::class));
        $this->assertTrue(class_exists(Asset::class));
        $this->assertTrue(class_exists(Risk::class));
        $this->assertTrue(class_exists(Service::class));

        $userFillable = (new User())->getFillable();
        $this->assertContains('role', $userFillable);
        $this->assertContains('management', $userFillable);

        $this->assertTrue(method_exists(Asset::class, 'risks'));
        $this->assertTrue(method_exists(Asset::class, 'services'));
        $this->assertTrue(method_exists(Risk::class, 'assets'));
        $this->assertTrue(method_exists(Risk::class, 'services'));
        $this->assertTrue(method_exists(Service::class, 'assets'));
        $this->assertTrue(method_exists(Service::class, 'risks'));
    }
}
