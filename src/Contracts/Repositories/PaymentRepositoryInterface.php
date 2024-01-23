<?php

namespace Digkill\YooKassaLaravel\Contracts\Repositories;

use Digkill\YooKassaLaravel\Models\YookassaPayment;

interface PaymentRepositoryInterface
{
    public function updateByPaymentId(string $paymentId, array $data): bool;

    public function findByPaymentId(string $paymentId): ?YookassaPayment;

}
