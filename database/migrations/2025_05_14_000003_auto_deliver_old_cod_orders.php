<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $now = now();
        DB::table('orders')
            ->where('payment_method', 'cod')
            ->where('status', 'processed')
            ->whereNotNull('delivery_date')
            ->whereDate('delivery_date', '<=', $now->toDateString())
            ->update([
                'status' => 'delivered',
                'delivered_at' => $now,
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally revert delivered COD orders back to processed if needed
        // (This is a no-op for safety)
    }
}; 