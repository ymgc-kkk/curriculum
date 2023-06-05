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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company');
            $table->string('company_ruby');
            $table->string('address');
            $table->string('phone_number');
            $table->string('ceo');
            $table->string('ceo_ruby');
            $table->string('billing');
            $table->string('billing_ruby');
            $table->string('department');
            $table->string('to');
            $table->string('to_ruby');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
