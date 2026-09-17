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
        Schema::create('guarantees', function (Blueprint $table) {
            $table->id();
            $table->integer('ssn');
            $table->string('mobile');
            $table->foreignId('mosque_id')->constrained('mosque');
            $table->foreignId('department_id')->constrained('department');
            $table->string('home');
            $table->string('guarantor');
            $table->string('guarantor_start')->nullable();
            $table->string('guarantor_end')->nullable();
            $table->double('amount', 15, 2)->nullable();
            $table->string('status')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guarantees');
    }
};
