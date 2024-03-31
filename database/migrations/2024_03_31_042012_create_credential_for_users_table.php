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
        Schema::create('credential_for_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // Add dynamic columns here
            $table->timestamps(); // Adding the timestamps as the last column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credential_for_users');
    }
};
