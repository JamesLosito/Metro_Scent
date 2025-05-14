<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        DB::table('orders')
            ->where('payment_method', 'stripe')
            ->whereIn('status', ['paid', 'processed'])
            ->whereNotNull('delivery_date')
            ->whereDate('delivery_date', '<=', $now->toDateString())
            ->update([
                'status' => 'delivered',
                'delivered_at' => $now,
            ]);
    }

    public function down(): void
    {
        // No-op for safety
    }
}; 