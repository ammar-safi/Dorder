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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("order_id");
            $table->text("content");
            $table->integer("quantity");
            $table->double("price");
            $table->double("total");

            $table->unsignedBigInteger("deliver_id");
            $table->foreign("deliver_id")
                ->references("id")
                ->on("users")
                ->onDelete('cascade');

            $table->unsignedBigInteger("client_id");
            $table->foreign("client_id")
                ->references("id")
                ->on("users")
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('invoices', function (Blueprint $table) {
        //     $table->dropForeign(["deliver_id"]);
        //     $table->dropColumn(["deliver_id"]);

        //     $table->dropForeign(["client_id"]);
        //     $table->dropColumn(["client_id"]);
        // });
        Schema::dropIfExists('invoices');
    }
};
