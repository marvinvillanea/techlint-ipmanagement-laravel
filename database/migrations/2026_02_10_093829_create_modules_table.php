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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('table');
            $table->string('primary_id');
            $table->string('unique_field');
            $table->integer('status')->default(1);
            $table->integer('deleted')->default(0);
            $table->unsignedBigInteger('latest_edit_by')->nullable(); // foreign key / last editor
            $table->unsignedBigInteger('added_by')->nullable(); // foreign key / last editor
            $table->timestamps();
        });



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
