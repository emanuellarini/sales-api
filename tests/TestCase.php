<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Dingo\Api\Routing\Helpers;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, Helpers;
}
