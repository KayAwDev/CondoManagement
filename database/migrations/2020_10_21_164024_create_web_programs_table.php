<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Grammars\CustomGrammar;
use App\Schemas\Blueprints\CustomBlueprint;

class CreateWebProgramsTable extends Migration
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

        $schema->create('web_programs', function (CustomBlueprint $table) {
            $table->varchar('ProgramName', 40)->primary();
            $table->varchar('ParentProgramName', 40)->nullable();
            $table->string('MenuName', 50)->nullable();
            $table->smallInteger('MenuSequence')->nullable();
            $table->boolean('Active')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('web_programs');
    }
}
