<?php

namespace Digkill\YooKassaLaravel\DTO;
class CreatePaymentResponseDTO
{
    public function __construct(
        public string $order_id,
        public string $user_id,
        public string $payment_id,
        public string $status,
        public string $description,
        public bool   $paid,
        public string $sum,
        public string $currency,
        public string $payment_link,
        public string $created_at,
        public string $updated_at,
    )
    {

    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
