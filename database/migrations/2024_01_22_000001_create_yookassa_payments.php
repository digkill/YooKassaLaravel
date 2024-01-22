<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('yookassa_payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('payment_id');
            $table->uuid('order_id');
            $table->boolean('is_paid')->default(false);
            $table->dateTime('paid_at')->nullable();
            $table->string('confirmation_url')->nullable();
            $table->enum('status', ['pending', 'waiting_for_capture', 'succeeded', 'canceled'])->nullable();
            $table->unsignedFloat('amount');
            $table->string('currency');
            $table->string('description')->nullable();
            $table->json('metadata')->nullable();
            $table->unsignedInteger('recipient_account_id')->nullable();
            $table->unsignedInteger('recipient_gateway_id')->nullable();
            $table->boolean('is_refundable');
            $table->boolean('test');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yookassa_payments');
    }
};
