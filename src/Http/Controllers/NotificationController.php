<?php

namespace Digkill\YooKassaLaravel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Digkill\YooKassaLaravel\Enums\PaymentStatus;
use Digkill\YooKassaLaravel\Events\YookassaPaymentNotification;
use Digkill\YooKassaLaravel\Http\Requests\NotificationRequest;
use Digkill\YooKassaLaravel\Services\PaymentService;
use YooKassa\Model\Notification\NotificationCanceled;
use YooKassa\Model\Notification\NotificationEventType;
use YooKassa\Model\Notification\NotificationSucceeded;
use YooKassa\Model\Notification\NotificationWaitingForCapture;

final class NotificationController extends Controller
{
    public function __construct(private readonly PaymentService $paymentService)
    {
    }

    public function index(NotificationRequest $request): JsonResponse
    {
        $requestBody = $request->all();
        if (!isset($requestBody['event'])) {
            throw new \Exception('event not found');
        }

        if (($requestBody['event'] === NotificationEventType::PAYMENT_SUCCEEDED)) {
            $notification = new NotificationSucceeded($requestBody);
        } elseif ($requestBody['event'] === NotificationEventType::PAYMENT_WAITING_FOR_CAPTURE) {
            $notification = new NotificationWaitingForCapture($requestBody);
        } else {
            $notification = new NotificationCanceled($requestBody);
        }

        $payment = $notification->getObject();
        $status = PaymentStatus::tryFrom($payment->getStatus());
        $this->paymentService->setStatus($payment->getId(), $status);
        $yooKassaPayment = $this->paymentService->findByPaymentId($payment->getId());
        YookassaPaymentNotification::dispatch($yooKassaPayment);

        return response()->json();
    }
}
