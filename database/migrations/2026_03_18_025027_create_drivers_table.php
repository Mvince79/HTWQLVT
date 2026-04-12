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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('phone')->nullable();
            $table->string('license_number')->unique()->nullable();
            $table->string('license_type')->nullable();
            $table->date('license_expiry')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('working_status', ['idle', 'assigned', 'delivering', 'resting', 'inactive'])->default('idle');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
