<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('duration_minutes')->default(30);
            $table->string('price_label')->nullable(); // free text: "Rp 150.000", "Mulai Rp 100.000", "Gratis"
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // "Staff" is whoever performs the service: doctor, therapist, barber, a room... the label is a setting.
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title')->nullable(); // e.g. "Dokter Gigi", "Senior Barber"
            $table->text('bio')->nullable();
            $table->string('photo_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('service_staff', function (Blueprint $table) {
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->primary(['service_id', 'staff_id']);
        });

        // Weekly working hours. A weekday can have several ranges (morning + afternoon = a break in between).
        Schema::create('staff_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->unsignedTinyInteger('weekday'); // 1 = Monday ... 7 = Sunday (ISO)
            $table->string('start_time', 5); // HH:MM
            $table->string('end_time', 5);
            $table->index(['staff_id', 'weekday']);
        });

        // Leave and holidays. staff_id null = the whole business is closed.
        Schema::create('time_offs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->nullable()->constrained('staff')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_offs');
        Schema::dropIfExists('staff_schedules');
        Schema::dropIfExists('service_staff');
        Schema::dropIfExists('staff');
        Schema::dropIfExists('services');
    }
};
