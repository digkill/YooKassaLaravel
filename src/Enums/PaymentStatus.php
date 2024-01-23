<?php

namespace Digkill\YooKassaLaravel\Enums;

enum PaymentStatus: string
{
    case SUCCEEDED = 'succeeded';
    case CANCELED = 'canceled';
    case PENDING = 'pending';
    case  WAITING_FOR_CAPTURE = 'waiting_for_capture';
}
