<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class YookassaPayment
 * @package App\Entities
 *
 * @ORM\Entity()
 * @ORM\Table(name="yookassa_payments", uniqueConstraints={
 *         @ORM\UniqueConstraint(name="unique_yookassa_payments_payment_id", columns={"payment_id"})
 *     }, indexes={
 *         @ORM\Index(name="index_yookassa_payments_user_id", columns={"user_id"}),
 *         @ORM\Index(name="index_yookassa_payments_payment_id", columns={"payment_id"}),
 *         @ORM\Index(name="index_yookassa_payments_order_id", columns={"order_id"})
 *     }
 * )
 */
class YookassaPayment
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="SET NULL", nullable=true, options={"unsigned"=true})
     */
    private $user_id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $payment_id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $order_id;

    /**
     * @ORM\Column(name="paid_at", type="datetime", nullable=true)
     */
    private $paid_at;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $confirmation_url;

    /**
     * @var string
     * @ORM\Column(name="status", type="string", length=40, nullable=true)
     */
    private $status;

    /**
     * @var string
     * @ORM\Column(name="status_refund", type="string", length=40, options={"default"="not_refunded"})
     */
    private $status_refund;

    /**
     * @var float
     * @ORM\Column(type="decimal", precision=12, scale=2, options={"default"=0})
     */
    private $amount;

    /**
     * @var float
     * @ORM\Column(type="decimal", precision=12, scale=2, options={"default"=0})
     */
    private $refund_amount;

    /**
     * @var integer
     * @ORM\Column(name="currency", type="string", length=10, options={"default"="RUB"})
     */
    private $currency;

    /**
     * @var integer
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(type="json", nullable=true)
     */
    private $metadata;

    /**
     * @var integer
     * @ORM\Column(type="integer", options={"unsigned"=true}, nullable=true)
     */
    private $recipient_account_id;

    /**
     * @var integer
     * @ORM\Column(type="integer", options={"unsigned"=true}, nullable=true)
     */
    private $recipient_gateway_id;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    private $is_refundable;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    private $is_test;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    private $is_paid;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deleted_at;
}
