ً<?php

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
        Schema::table('Users', function (Blueprint $table) {
            $table->foreign("package_id")
                ->references("id")
                ->on("packages")
                ->onDelete("cascade");

            $table->foreign("area_id")
                ->references("id")
                ->on("areas")
                ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Users', function (Blueprint $table) {
            $table->dropForeign(["package_id"]);
            // $table->dropColumn(["package_id"]);

            $table->dropForeign(["area_id"]);
            // $table->dropColumn(["area_id"]);
        });
    }
};
