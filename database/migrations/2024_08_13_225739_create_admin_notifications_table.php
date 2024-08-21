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
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("body");
            
            $table->unsignedBigInteger("order_id");
            $table->foreign("order_id")
                ->references("id")
                ->on("orders")
                ->onDelete("cascade");

            $table->unsignedBigInteger("admin_id");
            $table->foreign("admin_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");

            $table->boolean("read");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('admin_notifications', function (Blueprint $table) {

        //     $table->dropForeign(["order_id"]);
        //     $table->dropColumn(["order_id"]);

        //     $table->dropForeign(["admin_id"]);
        //     $table->dropColumn(["admin_id"]);

        // });
        Schema::dropIfExists('admin_notifications');
    }
};
