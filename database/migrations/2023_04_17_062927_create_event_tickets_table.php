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
        Schema::create('event_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId("event_id");
            $table->string("category");
            $table->string("name");
            $table->integer("stock")->default(100);
            $table->integer("amount_per_transaction")->default(5);
            $table->integer("price")->default(0);
            $table->dateTime("start_date");
            $table->dateTime("end_date");
            $table->boolean("on_sale")->default(true)->commend("true or false");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_tickets');
    }
};
