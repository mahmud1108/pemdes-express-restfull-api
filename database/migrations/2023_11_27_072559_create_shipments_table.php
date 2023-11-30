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
        Schema::create('shipments', function (Blueprint $table) {
            $table->uuid('no_receipts')->unique()->primary();
            $table->string('senders_name', 50);
            $table->string('senders_phone', 20);
            $table->text('senders_address');
            $table->string('weight');
            $table->string('total_cost');
            $table->text('item_name');
            $table->text('destination_address');
            $table->string('receivers_name', 50);
            $table->string('receivers_phone', 20);
            $table->enum('delivery_status', ['diproses', 'dalam pengiriman', 'diterima']);
            $table->enum('payment_status', ['belum dibayar', 'telah dibayar']);
            $table->string('village_destination');
            $table->foreign('village_destination')->on('villages')->references('village_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('current_bumdes');
            $table->foreign('current_bumdes')->on('bumdes')->references('bumdes_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->dateTime('date_address');
            $table->string('courier_id')->nullable();
            $table->foreign('courier_id')->on('couriers')->references('courier_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('acknowledgment')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
