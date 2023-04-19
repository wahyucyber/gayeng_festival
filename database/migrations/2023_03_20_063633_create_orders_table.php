<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer("invoice_index")->default(1);
            $table->string("invoice")->unique();
            $table->integer("pay");
            $table->string("payment_type")->comment("bri_va, bni_va, mandiri_va, bca_va, permata_va, qris, indomaret and alfamart");
            $table->text("payment_response")->nullable();
            $table->string("payment_status")->comment("settlement, pending, expire, cancel, deny and refund")->default("pending");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
