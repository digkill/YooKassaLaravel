<?php

namespace Digkill\YooKassaLaravel\Payment;

use Carbon\Carbon;

use Digkill\YooKassaLaravel\DTO\CreatePaymentResponseDTO;
use YooKassa\Request\Payments\CreatePaymentResponse;

class CreatePayment
{

    public function __construct(private ?CreatePaymentResponse $response, private $orderId, private $userId)
    {

    }

    public function get()
    {
        // Create Carbon Now
        $times = Carbon::now();

        return (new CreatePaymentResponseDTO(
            $this->orderId,
            $this->userId,
            $this->response->getId(),
            $this->response->getStatus(),
            $this->response->getDescription(),
            $this->response->getPaid(),
            $this->response->getAmount()->getIntegerValue() / 100,
            $this->response->getAmount()->getCurrency(),
            $this->response->confirmation->getConfirmationUrl(),
            $times->toDateTimeString(),
            $times->toDateTimeString()
        ));
    }

}
