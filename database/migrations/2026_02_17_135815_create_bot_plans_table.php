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
        Schema::create('bot_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('min_amount', 20, 8)->default(0);
            $table->decimal('max_amount', 20, 8)->nullable(); // Nullable for unlimited
            $table->integer('trades_count'); // Total number of trades
            $table->decimal('roi_per_trade', 8, 4); // Percentage, e.g., 0.5%
            $table->integer('duration_days'); // Duration in days
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_plans');
    }
};
