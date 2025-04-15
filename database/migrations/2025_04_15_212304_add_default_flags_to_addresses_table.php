<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->boolean('is_default_shipping')->default(false)->after('type');
            $table->boolean('is_default_billing')->default(false)->after('is_default_shipping');
        });
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn(['is_default_shipping', 'is_default_billing']);
        });
    }
};
