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
        Schema::create('aid_beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aid_id')->constrained('aids')->onDelete('cascade');
            $table->string('beneficiary_type')->nullable(); // orphan, widow, external
            $table->unsignedBigInteger('beneficiary_id')->nullable(); // Nullable للمستفيد الخارجي
            $table->string('ssn');
            $table->string('name');
            $table->string('mobile')->nullable();
            $table->string('wallet_number')->nullable();
            $table->string('mosque');
            $table->string('department');
            $table->timestamps();

            // منع تكرار نفس رقم الهوية داخل نفس المساعدة
            $table->unique(['aid_id', 'ssn'], 'aid_beneficiary_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aid_beneficiaries');
    }
};
