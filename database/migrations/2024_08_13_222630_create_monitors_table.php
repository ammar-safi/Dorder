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
        Schema::create('monitors', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger("monitor_id");
            $table->foreign("monitor_id")
                ->references("id")
                ->on("users")
                ->onDelete('cascade');

            $table->unsignedBigInteger("area_id") ;
            $table->foreign("area_id")
                ->references("id")
                ->on("areas")
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table("monitors" , function (Blueprint $table) {
        //     $table->dropForeign(["monitor_id"]);
        //     $table->dropColumn(["monitor_id"]);
        //     $table->dropForeign(["area_id"]);
        //     $table->dropColumn(["area_id"]);
        // });
        Schema::dropIfExists('monitors');
    }
};
