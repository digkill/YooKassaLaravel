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
        public bool   $is_paid,
        public bool   $is_test,
        public string $amount,
        public string $currency,
        public ?string $payment_link,
        public string $metadata,
        public ?string $recipient_account_id,
        public ?string $recipient_gateway_id,
        public bool   $is_refundable,
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
