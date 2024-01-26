<?php

namespace Digkill\YooKassaLaravel\Repositories;

use Exception;
use Digkill\YooKassaLaravel\Contracts\Repositories\PaymentRepositoryInterface;
use Digkill\YooKassaLaravel\Models\YookassaPayment;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * @method YookassaPayment getModel()
 */
class PaymentRepository implements PaymentRepositoryInterface
{
    protected string $class = YookassaPayment::class;

    public function updateByPaymentId(string $paymentId, array $data): bool
    {
        Log::error(['paymentId' => $paymentId]);
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
}
