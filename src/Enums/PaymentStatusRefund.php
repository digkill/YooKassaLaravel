<?php

namespace Digkill\YooKassaLaravel\Enums;

enum PaymentStatusRefund: string
{
    case NOT_REFUNDED = 'not_refunded';
    case  REFUNDED = 'refunded';
    case  PARTIAL_REFUNDED = 'partial_refunded';
}
