<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCropIdToHarvestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('harvests', function (Blueprint $table) {
            $table->unsignedBigInteger('crop_id')->after('id'); // Ajouter la colonne crop_id après l'id
            $table->foreign('crop_id')->references('id')->on('crops')->onDelete('cascade'); // Clé étrangère vers crops
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('harvests', function (Blueprint $table) {
            $table->dropForeign(['crop_id']); // Supprimer la contrainte de clé étrangère
            $table->dropColumn('crop_id'); // Supprimer la colonne crop_id
        });
    }
}
