<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        DB::delete('delete from sub_districts');
        DB::delete('delete from villages');
        DB::delete('delete from bumdes');
        DB::delete('delete from shipments');
        DB::delete('delete from couriers');
        DB::delete('delete from admins');
    }
}
