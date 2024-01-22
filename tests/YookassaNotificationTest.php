<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Nos\Yookassa\Enums\Currency;
use Nos\Yookassa\Models\YookassaPayment;
use Tests\TestCase;

final class YookassaNotificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Index LanguageLine functionality
     *
     * @return void
     */
    public function testNotification(): void
    {
        $payment = new YookassaPayment();
        $payment->id = "22d6d597-000f-5000-9000-145f6df21d6f";
        $payment->description = "Заказ №72";
        $payment->amount = 2;
        $payment->currency = Currency::RUB->name;
        $payment->refundable = true;
        $payment->test = true;
        $payment->save();

        $data = [
            "type" => "notification",
            "event" => "payment.waiting_for_capture",
            "object" => [
                "id" => "22d6d597-000f-5000-9000-145f6df21d6f",
                "status" => "waiting_for_capture",
                "paid" => true,
                "amount" => [
                    "value" => "2.00",
                    "currency" => "RUB"
                ],
                "authorization_details" => [
                    "rrn" => "10000000000",
                    "auth_code" => "000000",
                    "three_d_secure" => [
                        "applied" => true
                    ]
                ],
                "created_at" => "2018-07-10T14:27:54.691Z",
                "description" => "Заказ №72",
                "expires_at" => "2018-07-17T14:28:32.484Z",
                "metadata" => [
                ],
                "payment_method" => [
                    "type" => "bank_card",
                    "id" => "22d6d597-000f-5000-9000-145f6df21d6f",
                    "saved" => false,
                    "card" => [
                        "first6" => "555555",
                        "last4" => "4444",
                        "expiry_month" => "07",
                        "expiry_year" => "2021",
                        "card_type" => "MasterCard",
                        "issuer_country" => "RU",
                        "issuer_name" => "Sberbank"
                    ],
                    "title" => "Bank card *4444"
                ],
                "recipient" => [
                    "account_id" => 1,
                    "gateway_id" => 1
                ],
                "refundable" => false,
                "test" => false
            ]
        ];

        $response = $this->postJson(route('yookassa.notifications'), $data);

        $response->assertStatus(200);
    }
}
