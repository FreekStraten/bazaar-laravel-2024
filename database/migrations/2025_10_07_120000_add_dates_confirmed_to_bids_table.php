<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bids', function (Blueprint $table) {
            if (!Schema::hasColumn('bids', 'dates_confirmed')) {
                $table->boolean('dates_confirmed')->default(false)->after('return_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bids', function (Blueprint $table) {
            if (Schema::hasColumn('bids', 'dates_confirmed')) {
                $table->dropColumn('dates_confirmed');
            }
        });
    }
};

