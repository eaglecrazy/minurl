<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UrlDatas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('urlDatas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table
                ->bigInteger('linkId')
                ->comment('Link id');
            $table
                ->string('browser', 100)
                ->nullable()
                ->comment('Browser name');
            $table
                ->string('country', 255)
                ->nullable()
                ->comment('Country name');
            $table
                ->string('city', 255)
                ->nullable()
                ->comment('City name');
            $table
                ->string('region', 255)
                ->nullable()
                ->comment('Region name');
            $table
                ->string('ip', 16)
                ->nullable()
                ->comment('IP adress');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('urlDatas');
    }
}
