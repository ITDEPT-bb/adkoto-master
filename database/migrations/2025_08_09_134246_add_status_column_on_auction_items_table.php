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
        Schema::table('auction_items', function (Blueprint $table) {
            $table->decimal('current_price', 12, 2)->nullable()->after('starting_price');
            $table->enum('status', ['upcoming','active','sold'])->default('upcoming')->after('current_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auction_items', function (Blueprint $table) {
             $table->dropColumn('current_price');
              $table->dropColumn('status');
        });
    }
};
