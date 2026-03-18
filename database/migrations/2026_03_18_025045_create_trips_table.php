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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();

            $table->foreignId('driver_id')
                ->nullable()
                ->constrained('drivers')
                ->onDelete('set null');

            $table->foreignId('vehicle_id')
                ->nullable()
                ->constrained('vehicles')
                ->onDelete('set null');

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();

            $table->enum('status', [
                'not_started',
                'in_progress',
                'completed',
                'cancelled'
            ])->default('not_started');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
