<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Grammars\CustomGrammar;
use App\Schemas\Blueprints\CustomBlueprint;

class CreateVisitorLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->setSchemaGrammar(new CustomGrammar());

        $schema = DB::connection()->getSchemaBuilder();

        $schema->blueprintResolver(function($table, $callback) {
            return new CustomBlueprint($table, $callback);
        });

        $schema->create('visitor_logs', function (CustomBlueprint $table) {
            $table->bigIncrements('VisitorLogID');
            $table->string('Visitor_Name', 200);
            $table->string('Visitor_ContactNumber', 100);
            $table->string('Visitor_NRIC', 15);
            $table->varchar('VisitPlace', 50);
            $table->bigInteger('Visit_UnitID')->nullable();
            $table->foreign('Visit_UnitID')->references('UnitID')->on('units');
            $table->dateTime('EnterDateTime');
            $table->dateTime('ExitDateTime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visitor_logs');
    }
}
