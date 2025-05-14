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
        Schema::table('orders', function (Blueprint $table) {
            $table->text('processing_notes')->nullable()->after('delivery_date');
            $table->timestamp('processed_at')->nullable()->after('processing_notes');
            $table->timestamp('cancelled_at')->nullable()->after('processed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['processing_notes', 'processed_at', 'cancelled_at']);
        });
    }
}; 