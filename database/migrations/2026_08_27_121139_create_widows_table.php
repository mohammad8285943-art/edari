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
        Schema::create('widows', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ssn')->unique();
            $table->string('mobile')->nullable();
            $table->string('job')->nullable();
            $table->string('husband');
            $table->string('h_ssn')->nullable();
            $table->string('date_death')->nullable();
            $table->string('address')->nullable();
            $table->string('h_job')->nullable();
            $table->integer('orphan_count')->default(0);
            $table->foreignId('mosque_id')->constrained('mosque');
            $table->foreignId('department_id')->constrained('department');
            $table->string('gaz_mobile')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('widows');
    }
};
