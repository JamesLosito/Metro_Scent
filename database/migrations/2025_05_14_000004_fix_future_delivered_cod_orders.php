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
            ->where('payment_method', 'cod')
            ->where('status', 'delivered')
            ->whereNotNull('delivery_date')
            ->whereDate('delivery_date', '>', $now->toDateString())
            ->update([
                'status' => 'processed',
                'delivered_at' => null,
            ]);
    }

    public function down(): void
    {
        // No-op for safety
    }
}; 