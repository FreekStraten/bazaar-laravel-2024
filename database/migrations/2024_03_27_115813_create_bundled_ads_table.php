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
//        Schema::create('ad_bundled_ad', function (Blueprint $table) {
//            $table->id();
//            $table->unsignedBigInteger('ad_id');
//            $table->unsignedBigInteger('bundled_ad_id');
//            $table->timestamps();
//
//            $table->unique(['ad_id', 'bundled_ad_id']);
//            $table->foreign('ad_id')->references('id')->on('ads')->onDelete('cascade');
//            $table->foreign('bundled_ad_id')->references('id')->on('bundled_ads')->onDelete('cascade');
//        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
//        Schema::dropIfExists('bundled_ads');
    }
};
