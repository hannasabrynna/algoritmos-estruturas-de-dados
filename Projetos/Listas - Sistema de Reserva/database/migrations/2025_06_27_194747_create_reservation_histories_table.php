<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservation_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->constrained('visitors')->onDelete('cascade');
            $table->foreignId('attraction_id')->constrained('attractions')->onDelete('cascade');
            $table->timestamp('reserved_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_histories');
    }
};
