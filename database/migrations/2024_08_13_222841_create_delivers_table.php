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
        Schema::create('delivers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("deliver_id");
            $table->foreign("deliver_id")
                ->references("id")
                ->on("users")
                ->onDelete('cascade');

            $table->unsignedBigInteger("area_id");
            $table->foreign("area_id")
                ->references("id")
                ->on("areas")
                ->onDelete('cascade');

            $table->unsignedBigInteger("monitor_id");
            $table->foreign("monitor_id")
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
        // Schema::table('delivers', function (Blueprint $table) {
        //     $table->dropForeign(["deliver_id"]);
        //     $table->dropColumn(["deliver_id"]);

        //     $table->dropForeign(["area_id"]);
        //     $table->dropColumn(["area_id"]);

        //     $table->dropForeign(["monitor_id"]);
        //     $table->dropColumn(["monitor_id"]);
        // });
        Schema::dropIfExists('delivers');
    }
};
