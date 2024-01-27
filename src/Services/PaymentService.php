<?php

namespace Digkill\YooKassaLaravel\Services;

use Digkill\YooKassaLaravel\Enums\PaymentStatus;
use Digkill\YooKassaLaravel\Models\YookassaPayment;
use Digkill\YooKassaLaravel\YooKassa;
use Digkill\YooKassaLaravel\Contracts\Repositories\PaymentRepositoryInterface;
use YooKassa\Common\Exceptions\ApiConnectionException;
use YooKassa\Common\Exceptions\ApiException;
use YooKassa\Common\Exceptions\AuthorizeException;
use YooKassa\Common\Exceptions\BadApiRequestException;
use YooKassa\Common\Exceptions\ExtensionNotFoundException;
use YooKassa\Common\Exceptions\ForbiddenException;
use YooKassa\Common\Exceptions\InternalServerError;
use YooKassa\Common\Exceptions\NotFoundException;
use YooKassa\Common\Exceptions\ResponseProcessingException;
use YooKassa\Common\Exceptions\TooManyRequestsException;
use YooKassa\Common\Exceptions\UnauthorizedException;
use YooKassa\Request\Refunds\CreateRefundResponse;

/**
 * @method PaymentRepositoryInterface getRepository()
 */
final class PaymentService
{
    public function __construct(
        private YooKassa                   $yooKassa,
        private PaymentRepositoryInterface $paymentRepository,
    )
    {
    }

    /**
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws ApiException
     * @throws ExtensionNotFoundException
     * @throws BadApiRequestException
     * @throws AuthorizeException
     * @throws InternalServerError
     * @throws ForbiddenException
     * @throws TooManyRequestsException
     * @throws ApiConnectionException
     * @throws UnauthorizedException
     */
    public function create(
        float  $amount,
        string $description = '',
        $orderId = null,
        int    $userId = null,
        string $currency = 'RUB',
        bool   $capture = true
    ): YookassaPayment
    {
        $payment = $this->yooKassa->createPayment($amount, $description, $orderId, $userId, $currency, $capture);

        return $this->paymentRepository->create([
            'user_id' => $payment->user_id,
            'payment_id' => $payment->payment_id,
            'order_id' => $payment->order_id,
            'confirmation_url' => $payment->payment_link,
            'status' => $payment->status,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'description' => $payment->description,
            'metadata' => $payment->metadata,
            'recipient_account_id' => $payment->recipient_account_id,
            'recipient_gateway_id' => $payment->recipient_gateway_id,
            'is_paid' => $payment->is_paid,
            'is_test' => $payment->is_test,
            'is_refundable' => $payment->is_refundable,
            'created_at' => $payment->created_at,
            'updated_at' => $payment->updated_at,
        ]);
    }

    /**
     * @param $orderId
     * @param $paymentid
     * @param $amount
     * @param string $currency
     * @return CreateRefundResponse
     */
    public function refund($orderId, $paymentid, $amount, string $currency = 'RUB'): YookassaPayment
    {
        $refund = $this->yooKassa->refund($orderId, $paymentid, $amount, $currency);

        /*dd([
            'user_id' => $payment->user_id,
            'payment_id' => $payment->payment_id,
            'order_id' => $payment->order_id,
            'confirmation_url' => $payment->payment_link,
            'status' => $payment->status,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'description' => $payment->description,
            'metadata' => $payment->metadata,
            'recipient_account_id' => $payment->recipient_account_id,
            'recipient_gateway_id' => $payment->recipient_gateway_id,
            'is_paid' => $payment->is_paid,
            'is_test' => $payment->is_test,
            'is_refundable' => $payment->is_refundable,
            'created_at' => $payment->created_at,
            'updated_at' => $payment->updated_at,
        ]);*/


        if ($refund->getStatus() !== 'succeeded') {
            throw new \Exception('Can not refund');
        }

        return $this->paymentRepository->refund($refund);
    }

    public function findByPaymentId(string $paymentId): ?YookassaPayment
    {
        return $this->paymentRepository->findByPaymentId($paymentId);
    }

    public function setStatus(string $paymentId, PaymentStatus $status): bool
    {
        return $this->paymentRepository->updateByPaymentId($paymentId, [
            'status' => $status->value
        ]);
    }
}
