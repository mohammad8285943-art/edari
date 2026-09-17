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
             $table->timestamps();
            $table->softDeletes();

            $table->string('personal_image')->nullable();
            $table->string('birth_image')->nullable();
            $table->string('father_image')->nullable();
            $table->string('mother_image')->nullable();
            $table->string('agent_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orphan', function (Blueprint $table) {
            $table->dropTimestamps();
            $table->dropSoftDeletes();

            $table->dropColumn([
                'personal_image',
                'birth_image',
                'father_image',
                'mother_image',
                'agent_image',
            ]);
        });
    }
};
