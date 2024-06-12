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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('content');    // contentカラム追加
            $table->timestamps();
            $table->string('status',10);
            
            $table->unsignedBigInteger('user_id');

            // 外部キー制約
            $table->foreign('user_id')->references('id')->on('users');
        });
        
        /*Schema::table('tasks', function (Blueprint $table) {
            $table->string('user_id');
        });*/
    }

    /**
     * Reverse the migrations.
     */
    /* 
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
    */
    
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
