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
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->dateTime('check_time');
            $table->double('gps_lat')->nullable();
            $table->double('gps_lng')->nullable();
            $table->text('photo_url')->nullable();
            $table->boolean('is_fake_gps')->default(false);
            $table->boolean('is_outside_geofence')->default(false);
            $table->boolean('synced')->default(false);
            $table->enum('check_type', ['IN', 'OUT']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
