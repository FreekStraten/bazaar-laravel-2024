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
        // 1) timestamps + indices + unique
        Schema::table('ad_reviews', function (Blueprint $table) {
            // timestamps toevoegen als ze nog niet bestaan
            if (!Schema::hasColumn('ad_reviews', 'created_at')) {
                $table->timestamps();
            }

            // rating: UNSIGNED TINYINT (1..255) — let op: ->change() vereist doctrine/dbal
            $table->unsignedTinyInteger('rating')->default(1)->change();

            // Eén review per (ad,user)
            $table->unique(['ad_id', 'user_id'], 'ad_reviews_ad_user_unique');

            // Duidelijke indexnamen zodat dropIndex straks werkt
            $table->index('ad_id', 'ad_reviews_ad_id_index');
            $table->index('user_id', 'ad_reviews_user_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_reviews', function (Blueprint $table) {
            $table->dropIndex(['ad_id']);
            $table->dropIndex(['user_id']);
            $table->dropUnique('ad_reviews_ad_user_unique');

            // check constraint terugdraaien wordt door Laravel niet altijd automatisch ondersteund;
            // laat dit achterwege of maak een raw statement als je deze echt wilt droppen.

            // timestamps weghalen:
            $table->dropColumn(['created_at','updated_at']);
        });
    }
};
