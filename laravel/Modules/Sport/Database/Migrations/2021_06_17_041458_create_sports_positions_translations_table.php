<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSportsPositionsTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sports_positions_translations', function (Blueprint $table) {
            $table->id();
            $table->integer('sport_position_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name');
            $table->unique(['sport_position_id', 'locale']);

            $table->foreign('sport_position_id')
                ->references('id')
                ->on('sports_positions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sports_positions_translations');
    }
}