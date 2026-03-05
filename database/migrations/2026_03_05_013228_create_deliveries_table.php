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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->integer('id_client');
            $table->integer('id_week');
            $table->integer('id_address')->nullable();
            $table->string('delivery_type')->default('local'); // local, point, postal
            $table->string('delivery_day')->nullable();
            $table->text('notes')->nullable();
            $table->string('delivery_status')->default('No ha Respondido'); // Revisando, Por entregar, Entregado, Enviado
            $table->string('payment_status')->default('Revisando y no pagado'); // Pagado y revisado
            $table->string('tracking_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
