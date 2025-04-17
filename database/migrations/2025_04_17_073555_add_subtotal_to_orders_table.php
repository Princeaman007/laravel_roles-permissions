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
            $table->decimal('subtotal', 10, 2)->default(0)->after('user_id');
            $table->decimal('tax', 10, 2)->default(0)->after('subtotal');
            $table->decimal('shipping_cost', 10, 2)->default(0)->after('tax');
            $table->decimal('discount', 10, 2)->default(0)->after('shipping_cost');
            $table->decimal('total', 10, 2)->default(0)->after('discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'subtotal',
                'tax',
                'shipping_cost',
                'discount',
                'total',
            ]);
        });
    }
};
