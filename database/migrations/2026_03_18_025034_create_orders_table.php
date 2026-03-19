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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('order_code', 50)->unique();

            $table->text('cargo_name');
            $table->float('cargo_weight');

            $table->text('pickup_location');
            $table->text('delivery_location');

            $table->dateTime('scheduled_date')->nullable();

            $table->enum('status', ['pending', 'assigned', 'delivering', 'completed', 'cancelled'])
                ->default('pending');

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
