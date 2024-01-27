<?php

namespace Digkill\YooKassaLaravel\Payment;

use Exception;
use YooKassa\Client;
use Digkill\YooKassaLaravel\YooKassa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
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
use YooKassa\Model\NotificationEventType;
use YooKassa\Model\Webhook\Webhook;
use YooKassa\Request\Payments\CreatePaymentResponse;
use YooKassa\Request\Webhook\WebhookListResponse;

class WebhookPayment
{

    /**
     * YooKassa Client
     *
     * @var Client
     */
    private Client $client;

    /**
     * API
     *
     * @var ?YooKassa
     */
    private ?YooKassa $api;

    public function __construct(?YooKassa $api = null)
    {
        $this->client = new Client();
        $this->client->setAuthToken('Bearer ' . env('YOOKASSA_CLIENT_ID', null) . ':' . Cache::get('yookassa_token'));
        $this->api = $api;
    }

    /**
     * Create Webhook
     *
     * @param string $url
     * @param string $event
     *
     * @return Webhook|null
     *
     * @throws ApiException
     * @throws BadApiRequestException
     * @throws ExtensionNotFoundException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function addWebhook(string $url, string $event = NotificationEventType::PAYMENT_SUCCEEDED)
    {
        return $this->client->addWebhook([
            'event' => $event,
            'url' => $url
        ]);
    }

    /**
     * Get List Webhooks
     *
     * @return WebhookListResponse|null
     * @throws ApiException
     * @throws BadApiRequestException
     * @throws ExtensionNotFoundException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     * @throws AuthorizeException
     */
    public function getWebhooks()
    {
        return $this->client->getWebhooks();
    }

    /**
     * Remove Webhook
     *
     * @param string $webhook_id
     *
     * @return Webhook|null
     * @throws ApiException
     * @throws BadApiRequestException
     * @throws ExtensionNotFoundException
     * @throws ForbiddenException
     * @throws InternalServerError
     * @throws NotFoundException
     * @throws ResponseProcessingException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function deleteWebhook(string $webhook_id)
    {
        return $this->client->removeWebhook($webhook_id);
    }

    public function read(Request $request, callable $successFunction = null, callable $failedFunction = null)
    {
        // request
        $data = $request->all();

        // Notification
        if (isset($data['type']) && $data['type'] == 'notification') {
            // Create Response
            $response = new CreatePaymentResponse();
            // Create Object to Array and Class
            $response->fromArray($data['object']);

            // Check Payment
            $this->api->checkPayment($data['object']['id'],
                $response->getMetadata()['order_id'],
                $data['object']['amount']['value'],
                $data['object']['amount']['currency'],

                function ($payment) use ($successFunction) {

                    if ($successFunction) {
                        try {
                            $successFunction($payment);
                        } catch (Exception $exception) {
                            Log::error($exception->getMessage());
                        }
                    }
                }, function ($payment) use ($failedFunction) {

                    if ($failedFunction) {
                        try {
                            $failedFunction($payment);
                        } catch (Exception $exception) {
                            Log::error($exception->getMessage());
                        }
                    }
                });
        }
    }

}
