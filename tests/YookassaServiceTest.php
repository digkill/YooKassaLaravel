<?php

namespace Tests\Feature;

use Digkill\YooKassaLaravel\Repositories\PaymentRepository;
use Digkill\YooKassaLaravel\Services\PaymentService;
use Digkill\YooKassaLaravel\YooKassa;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

final class YookassaServiceTest extends TestCase
{
    //use RefreshDatabase;

    /**
     * Test Index LanguageLine functionality
     *
     * @return void
     */
    public function testCreatePayment(): void
    {
        $paymentService = new PaymentService(
            app(YooKassa::class),
            app(PaymentRepository::class),
        );

        $model = $paymentService->create(1000, 'test', '1234567293', 1, 'RUB', true);
        $this->assertNotEmpty($model);
    }
}
