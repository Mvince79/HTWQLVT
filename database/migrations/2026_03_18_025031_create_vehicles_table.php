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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();

            $table->string('plate_number', 20);
            $table->enum('type', ['light_truck', 'medium_truck', 'heavy_truck', 'container_truck'])
                ->default('light_truck');

            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->enum('working_status', ['available', 'in_use', 'maintenance'])
                ->default('available');

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
