<?php

namespace Tests\Feature;

use Digkill\YooKassaLaravel\Facades\YooKassaFacade;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

final class YookassaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Index LanguageLine functionality
     *
     * @return void
     */
    public function testCreatePayment(): void
    {


        $model = YookassaFacade::createPayment(1000, 'test', '123456789', 1, 'RUB');
        $this->assertNotEmpty($model);
    }
}
