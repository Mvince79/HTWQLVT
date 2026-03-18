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
        Schema::create('fuel_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('trip_id')
                ->nullable()
                ->constrained('trips')
                ->onDelete('set null');

            $table->foreignId('vehicle_id')
                ->nullable()
                ->constrained('vehicles')
                ->onDelete('set null');

            $table->foreignId('driver_id')
                ->nullable()
                ->constrained('drivers')
                ->onDelete('set null');

            $table->decimal('fuel_amount', 10, 2);
            $table->text('note')->nullable();

            $table->timestamp('created_at')->useCurrent();
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
