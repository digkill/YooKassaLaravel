<?php

namespace Digkill\YooKassaLaravel\Repositories;

use Digkill\YooKassaLaravel\Enums\PaymentStatusRefund;
use Exception;
use Digkill\YooKassaLaravel\Contracts\Repositories\PaymentRepositoryInterface;
use Digkill\YooKassaLaravel\Models\YookassaPayment;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use YooKassa\Model\Refund\RefundInterface;

/**
 * @method YookassaPayment getModel()
 */
class PaymentRepository implements PaymentRepositoryInterface
{
    protected string $class = YookassaPayment::class;

    public function updateByPaymentId(string $paymentId, array $data): bool
    {
        return $this->getRepository()
            ->where(['payment_id' => $paymentId])
            ->update($data);
    }

    public function findByPaymentId(string $paymentId): ?YookassaPayment
    {
        return $this->getRepository()
            ->where(['payment_id' => $paymentId])
            ->firstOrFail();
    }

    /**
     * @return YookassaPayment
     * @throws BindingResolutionException
     */
    public function getRepository(): YookassaPayment
    {
        return app()->make($this->class);
    }

    /**
     * @throws Exception
     */
    public function create(array $data): ?Model
    {
        $model = $this->getRepository()->create($data);

        if ($model === null) {
            throw new Exception(trans('exceptions.not_created'));
        }

        return $model;
    }

    /**
     * @throws BindingResolutionException
     */
    public function refund(RefundInterface $refund): YookassaPayment
    {
        $yookassaPayment = $this->getRepository()
            ->where(['payment_id' => $refund->getId()])
            ->first();

        $yookassaPayment->refund_amount = $yookassaPayment->refund_amount + $refund->getAmount();

        if ($yookassaPayment->refund_amount > $yookassaPayment->amount) {
            throw new \Exception('The refund amount is greater than the payment amount');
        }

        if ($yookassaPayment->amount === $yookassaPayment->refund_amount) {
            $yookassaPayment->status = PaymentStatusRefund::REFUNDED->value;
        } else {
            $yookassaPayment->status = PaymentStatusRefund::PARTIAL_REFUNDED->value;
        }

        $yookassaPayment->save();
        return $yookassaPayment;
    }
}
