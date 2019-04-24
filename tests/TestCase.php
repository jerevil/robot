<?php

namespace Tests;

use Laravel\Lumen\Application;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\TestCase as LumenTestCase;

abstract class TestCase extends LumenTestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication(): Application
    {
        return require __DIR__.'/../bootstrap/app.php';
    }
}
