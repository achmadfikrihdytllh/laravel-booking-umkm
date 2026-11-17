<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('code', 12)->unique();
            $table->foreignId('service_id')->constrained()->restrictOnDelete();
            $table->foreignId('staff_id')->constrained('staff')->restrictOnDelete();
            $table->string('customer_name');
            $table->string('customer_phone', 20); // digits only, country code first: 628123456789
            $table->string('customer_email')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->string('status', 20)->default('pending'); // pending | confirmed | cancelled | completed | no_show
            $table->text('notes')->nullable();       // written by the customer
            $table->text('admin_notes')->nullable(); // internal
            $table->string('locale', 5)->default('id');
            $table->timestamps();

            $table->index(['staff_id', 'starts_at']);
            $table->index('starts_at');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
