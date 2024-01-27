<?php

namespace Digkill\YooKassaLaravel;

use YooKassa\Client;
use Digkill\YooKassaLaravel\Enums\Currency;
use Digkill\YooKassaLaravel\Enums\PaymentStatus;
use Digkill\YooKassaLaravel\Payment\CodesPayment;
use Digkill\YooKassaLaravel\Payment\CreatePayment;
use Digkill\YooKassaLaravel\Payment\WebhookPayment;
use YooKassa\Common\Exceptions\ApiException;
use YooKassa\Common\Exceptions\BadApiRequestException;
use YooKassa\Common\Exceptions\ExtensionNotFoundException;
use YooKassa\Common\Exceptions\ForbiddenException;
use YooKassa\Common\Exceptions\InternalServerError;
use YooKassa\Common\Exceptions\NotFoundException;
use YooKassa\Common\Exceptions\ResponseProcessingException;
use YooKassa\Common\Exceptions\TooManyRequestsException;
use YooKassa\Common\Exceptions\UnauthorizedException;
use YooKassa\Request\Refunds\CreateRefundResponse;

class YooKassa
{
    public const YOOKASSA_NAME_CONFIG = 'yookassa';

    /**
     * Configuration YooKassa
     *
     * @var array
     */
    private array $config;

    /**
     * YooKassa Client
     *
     * @var Client
     */
    private Client $client;

    public function __construct(array $config = [])
    {
        $configYooKassa = config(self::YOOKASSA_NAME_CONFIG) ?? [];
        // Configuration
        $this->config = [...$config, ...$configYooKassa];

        // Create Client
        $this->client = new Client();

        // Create Authorization
        $this->client->setAuth($this->config['shop_id'], $this->config['secret_key']);
    }

    /**
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws ApiException
     * @throws BadApiRequestException
     * @throws ExtensionNotFoundException
     * @throws InternalServerError
     * @throws ForbiddenException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     * @throws \Exception
     */
    public function createPayment(float    $amount,
                                  string   $description,
                                  string   $orderId = null,
                                  int      $userId = null,
                                  string   $currency = null,
                                  bool     $capture = true,
                                  callable $callback = null): DTO\CreatePaymentResponseDTO
    {
        if ($orderId === null) {
            // Generate orderId
            $orderId = uniqid('', true);
        }

        if ($currency === null) {
            $currency = Currency::RUB->value;
        }


        // Redirect URI
        if (empty($this->config['redirect_uri'])) {
            throw new \Exception('Not redirect uri');
        }

        $redirectUriParse = parse_url($this->config['redirect_uri']);

        $redirectUrl = $redirectUriParse['scheme']
            . '://'
            . $redirectUriParse['host']

            . $redirectUriParse['path']
            . '?order_id=' . $orderId;

        $response = $this->client->createPayment([
            'amount' => [
                'value' => $amount,
                'currency' => $currency
            ],
            'confirmation' => [
                'type' => 'redirect',
                'return_url' => $redirectUrl
            ],
            'metadata' => [
                'order_id' => $orderId
            ],
            'capture' => $capture,
            'description' => $description,
        ], $orderId);

        if ($callback !== null) {
            $callback($response);
        }

        // Create Request
        return (new CreatePayment($response, $orderId, $userId))->get();

    }

    public function checkPayment(string $paymentId, $orderId, $amount, $currency, callable $success, callable $failed = null)
    {
        // Get Payment Info
        $payment = $this->client->getPaymentInfo($paymentId);

        // Validation Payment Life
        if ($payment->getStatus() === PaymentStatus::WAITING_FOR_CAPTURE->value) {
            $response = $this->client->capturePayment([
                'amount' => [
                    'value' => $amount,
                    'currency' => $currency,
                ],
            ], $paymentId, $orderId);

            if ($response->getStatus() === PaymentStatus::SUCCEEDED->value) {
                return $success($response);
            } else {
                if ($failed) {
                    return $failed($payment);
                }

                return [
                    'error' => 'Canceled Invoice',
                    'code' => CodesPayment::CANCELED_INVOICE
                ];
            }
        } elseif ($payment->getStatus() == 'succeeded') {
            return $success($payment);
        } else {
            if ($failed) {
                return $failed($payment);
            }

            return [
                'error' => 'Canceled Invoice',
                'code' => CodesPayment::CANCELED_INVOICE
            ];
        }
    }

    public function refund($orderId, $paymentid, $amount, $currency = 'RUB'): CreateRefundResponse
    {
        return $this->client->createRefund(
            array(
                'amount' => [
                    'value' => $amount,
                    'currency' => $currency,
                ],
                'payment_id' => $paymentid,
            ),
            $orderId
        );
    }

    public function webhook()
    {
        return new WebhookPayment($this);
    }
}
