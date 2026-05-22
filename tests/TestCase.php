<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Saat test, kita tidak butuh asset Vite (CSS/JS bundle). Memanggil
     * `withoutVite()` mencegah error "Vite manifest not found" di environment
     * CI yang tidak menjalankan `npm run build`.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }
}
