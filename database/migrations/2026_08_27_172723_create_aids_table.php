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
        Schema::create('aids', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->enum('beneficiary_type', ['orphan', 'widow']);
            $table->double('amount', 15, 2)->default(0);
            $table->integer('count')->default(0);
            $table->string('donor')->nullable();
            $table->string('execution_place')->nullable();
            $table->date('date_execution')->nullable();
            $table->date('date_nomination')->nullable();
            $table->enum('status', ['nominated', 'executed'])->default('nominated');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aids');
    }
};
