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
        Schema::create('user_general_notifications', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("client_id");
            $table->foreign("client_id")
                ->references("id")
                ->on("users")
                ->onDelete('cascade');

            $table->string("title");
            $table->string("description");
            $table->unsignedBigInteger("order_id");
            $table->boolean("read");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('user_general_notifications', function (Blueprint $table) {
        //     $table->dropForeign(["client_id"]);
        //     $table->dropColumn(["client_id"]);
        // });
        Schema::dropIfExists('user_general_notifications');
    }
};
