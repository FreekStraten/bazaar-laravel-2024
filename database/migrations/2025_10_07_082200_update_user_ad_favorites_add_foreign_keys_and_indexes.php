<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ruim overbodige constraints/indexes op (indien aanwezig)
        // en zorg dat de unieke index bestaat.

        // Drop custom-named foreign keys/indexes
        try {
            if ($this->hasForeign('user_ad_favorites', 'uaf_user_fk')) {
                Schema::table('user_ad_favorites', function (Blueprint $table) {
                    $table->dropForeign('uaf_user_fk');
                });
            }
        } catch (\Throwable $e) {}

        try {
            if ($this->hasForeign('user_ad_favorites', 'uaf_ad_fk')) {
                Schema::table('user_ad_favorites', function (Blueprint $table) {
                    $table->dropForeign('uaf_ad_fk');
                });
            }
        } catch (\Throwable $e) {}

        try {
            if ($this->hasIndex('user_ad_favorites', 'uaf_user_idx')) {
                Schema::table('user_ad_favorites', function (Blueprint $table) {
                    $table->dropIndex('uaf_user_idx');
                });
            }
        } catch (\Throwable $e) {}

        try {
            if ($this->hasIndex('user_ad_favorites', 'uaf_ad_idx')) {
                Schema::table('user_ad_favorites', function (Blueprint $table) {
                    $table->dropIndex('uaf_ad_idx');
                });
            }
        } catch (\Throwable $e) {}

        // Ensure unique composite index on (user_id, ad_id)
        try {
            if (!$this->hasIndex('user_ad_favorites', 'user_ad_favorites_user_ad_unique')) {
                Schema::table('user_ad_favorites', function (Blueprint $table) {
                    $table->unique(['user_id', 'ad_id'], 'user_ad_favorites_user_ad_unique');
                });
            }
        } catch (\Throwable $e) {}
    }

    public function down(): void
    {
        // Herstel (re-add) de custom indexes en foreign keys indien gewenst
        try {
            Schema::table('user_ad_favorites', function (Blueprint $table) {
                try { $table->index('user_id', 'uaf_user_idx'); } catch (\Throwable $e) {}
                try { $table->index('ad_id',   'uaf_ad_idx'); } catch (\Throwable $e) {}

                try {
                    $table->foreign('user_id', 'uaf_user_fk')
                        ->references('id')->on('users')
                        ->cascadeOnDelete();
                } catch (\Throwable $e) {}

                try {
                    $table->foreign('ad_id', 'uaf_ad_fk')
                        ->references('id')->on('ads')
                        ->cascadeOnDelete();
                } catch (\Throwable $e) {}
            });
        } catch (\Throwable $e) {}
    }

    private function hasForeign(string $table, string $constraint): bool
    {
        try {
            $db = DB::getDatabaseName();
            $sql = "select constraint_name from information_schema.table_constraints where table_schema = ? and table_name = ? and constraint_name = ? and constraint_type = 'FOREIGN KEY'";
            return !empty(DB::select($sql, [$db, $table, $constraint]));
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function hasIndex(string $table, string $index): bool
    {
        try {
            $db = DB::getDatabaseName();
            $sql = "select index_name from information_schema.statistics where table_schema = ? and table_name = ? and index_name = ?";
            return !empty(DB::select($sql, [$db, $table, $index]));
        } catch (\Throwable $e) {
            return false;
        }
    }
};

