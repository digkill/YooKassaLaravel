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
            $this->response->getTest(),
            $this->response->getAmount()->getIntegerValue() / 100,
            $this->response->getAmount()->getCurrency(),
            $this->response->confirmation->getConfirmationUrl() ?? null,
            json_encode($this->response->getMetadata() ? $this->response->getMetadata()->toArray() : []),
            $this->response->getRecipient()->getAccountId(),
            $this->response->getRecipient()->getGatewayId(),
            $this->response->getRefundable(),
            $times->toDateTimeString(),
            $times->toDateTimeString(),
        ));
    }

}
