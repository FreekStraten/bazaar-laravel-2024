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
        Schema::table('ad_relations', function (Blueprint $table) {
            // unieke paren voorkomen (geen dubbele relaties in dezelfde richting)
            $table->unique(['ad_id', 'related_ad_id'], 'ad_relations_pair_unique');

            // extra indexen voor performance
            $table->index('ad_id', 'ad_relations_ad_id_index');
            $table->index('related_ad_id', 'ad_relations_related_ad_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ad_relations', function (Blueprint $table) {
            $table->dropUnique('ad_relations_pair_unique');
            $table->dropIndex(['ad_id']);
            $table->dropIndex(['related_ad_id']);
            // check constraint droppen is DB-specifiek; meestal kun je dit overslaan.
        });
    }
};
