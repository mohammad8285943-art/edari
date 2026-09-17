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
        Schema::table('orphan', function (Blueprint $table) {
            $table->string('wallet_number')->nullable()->after('mobile');
            $table->string('wallet_owner_name')->nullable()->after('wallet_number');
            $table->string('wallet_type')->nullable()->after('wallet_owner_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orphan', function (Blueprint $table) {
            $table->dropColumn([
                'wallet_number',
                'wallet_owner_name',
                'wallet_type',
            ]);
        });
    }
};
