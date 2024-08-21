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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("client_id");
            $table->foreign("client_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");

            $table->text("order");
            $table->enum("status", ["completed", "in_progress", "waiting"]);
            $table->string("uuid");

            $table->unsignedBigInteger("deliver_id")->nullable();
            $table->foreign("deliver_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");

            $table->unsignedBigInteger("address_id");
            $table->foreign("address_id")
                ->references("id")
                ->on("addresses")   
                ->onDelete("cascade");

            $table->timestamp("scheduled_time")->nullable();
            $table->string("estimated_time")->nullable();
            $table->string("start_deliver_time")->nullable();
            $table->string("received_time")->nullable();
            $table->boolean("canceled")->nullable();
            $table->string("canceled_note")->nullable();
            $table->string("image")->nullable();
            $table->integer("rate")->nullable();
            
            $table->boolean("is_voice")->nullable();
            $table->string("voice_URL")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('orders', function (Blueprint $table) {

        //     $table->dropForeign(["client_id"]);
        //     $table->dropColumn(["client_id"]);

        //     $table->dropForeign(["deliver_id"]);
        //     $table->dropColumn(["deliver_id"]);

        //     $table->dropForeign(["address_id"]);
        //     $table->dropColumn(["address_id"]);
        // });
        Schema::dropIfExists('orders');
    }
};
