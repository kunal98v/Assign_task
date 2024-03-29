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
        Schema::create('template_payloads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("template_id");
            $table->foreign("template_id")->references("id")->on('templates');
            $table->json("payload");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_payloads');
    }
};
