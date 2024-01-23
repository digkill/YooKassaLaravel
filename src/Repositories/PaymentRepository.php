<?php

namespace Digkill\YooKassaLaravel\Repositories;


use Digkill\YooKassaLaravel\Contracts\Repositories\PaymentRepositoryInterface;
use Digkill\YooKassaLaravel\Models\YookassaPayment;
use Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * @method YookassaPayment getModel()
 */
class PaymentRepository implements PaymentRepositoryInterface
{
    protected string $class = YookassaPayment::class;


    public function updateByPaymentId(string $paymentId, array $data): bool
    {
        return $this->getModel()
            ->findOrFail($paymentId)
            ->update($data);
    }

    public function findByPaymentId(string $paymentId): ?YookassaPayment
    {
        return $this->getModel()->find($paymentId);
    }

    public function getRepository()
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
