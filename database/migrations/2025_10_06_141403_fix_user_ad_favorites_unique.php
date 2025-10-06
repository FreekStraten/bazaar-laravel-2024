<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1) Verwijder duplicaten (laat de laagste id staan)
        DB::statement("
            DELETE uf1 FROM user_ad_favorites uf1
            INNER JOIN user_ad_favorites uf2
              ON uf1.user_id = uf2.user_id
             AND uf1.ad_id   = uf2.ad_id
             AND uf1.id > uf2.id
        ");

        // 2) Voeg een UNIQUE constraint toe zodat er nooit meer dubbelen komen
        Schema::table('user_ad_favorites', function (Blueprint $table) {
            $table->unique(['user_id', 'ad_id'], 'user_ad_favorites_user_ad_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_ad_favorites', function (Blueprint $table) {
            $table->dropUnique('user_ad_favorites_user_ad_unique');
        });
    }
};
