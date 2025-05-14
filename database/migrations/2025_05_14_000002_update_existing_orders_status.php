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
        // Update existing orders based on payment method
        DB::table('orders')->where('payment_method', 'stripe')
            ->where('status', '!=', 'cancelled')
            ->update(['status' => 'paid']);

        // Update existing COD orders that were processed to delivered
        DB::table('orders')->where('payment_method', 'cod')
            ->where('status', 'processed')
            ->update([
                'status' => 'delivered',
                'delivered_at' => DB::raw('processed_at')
            ]);

        // Update existing GCash orders that were processed
        DB::table('orders')->where('payment_method', 'gcash')
            ->where('status', 'processed')
            ->update([
                'processed_at' => DB::raw('COALESCE(processed_at, updated_at)')
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert COD orders back to processed
        DB::table('orders')->where('payment_method', 'cod')
            ->where('status', 'delivered')
            ->update([
                'status' => 'processed',
                'delivered_at' => null
            ]);

        // Revert Stripe orders to their original status
        // Note: We can't fully revert as we don't store the original status
        DB::table('orders')->where('payment_method', 'stripe')
            ->where('status', 'paid')
            ->update(['status' => 'processed']);
    }
}; 