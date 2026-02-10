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
        Schema::create('IPlist', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address')->unique();
            $table->string('type');
            $table->string('remarks');
            $table->integer('status')->default(1);
            $table->integer('deleted')->default(0);
            $table->unsignedBigInteger('latest_edit_by')->nullable();
            $table->unsignedBigInteger('added_by')->nullable(); // foreign key / last editor
            $table->timestamps();
        });

        DB::statement("
            CREATE VIEW v_iplist AS 
            SELECT `iplist`.`id`,
                `iplist`.`ip_address`,
                `iplist`.`type`,
                `iplist`.`remarks`,
                `iplist`.`status`,
                `iplist`.`deleted`,
                `iplist`.`latest_edit_by`,
                `iplist`.`added_by`,
                `iplist`.`created_at`,
                `iplist`.`updated_at`
            FROM `db_techlintv3`.`iplist`
            WHERE
                    (`iplist`.`deleted` = 0)
        ");
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('IPlist');
    }
};
