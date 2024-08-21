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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('mobile')->unique();
            $table->string('password');
            $table->string("uuid")->unique();
            $table->unsignedBigInteger("package_id")->nullable();
            $table->string("profile_image")->nullable();
            $table->integer("subscription_fees")->nullable();
            $table->enum('type' , ["client" , "deliver" , "monitor" , "admin"]);
            $table->boolean("active")->nullable() ;
            $table->date("expire")->nullable() ;
            $table->unsignedBigInteger("area_id")->nullable();
            $table->rememberToken();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
