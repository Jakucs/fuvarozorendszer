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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('pickup_address');
            $table->string('delivery_address');
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->enum('status', ['Kiosztva', 'Folyamatban', 'Elvégezve', 'Sikertelen'])->default('Kiosztva');
            $table->foreignId('carrier_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
