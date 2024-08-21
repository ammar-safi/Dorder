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
        Schema::create('rate_services', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("client_id");
            $table->foreign("client_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");

            $table->integer("rate");
            $table->text("review");

            $table->unsignedBigInteger("services_id");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('rate_services', function (Blueprint $table) {
        //     $table->dropForeign(["client_id"]);
        //     $table->dropColumn(["client_id"]);

        //     $table->dropColumn(["services_id"]);
            
        // });
        Schema::dropIfExists('rate_services');
    }
};
