<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Schemas\Grammars\CustomGrammar;
use App\Schemas\Blueprints\CustomBlueprint;

class RecreateWebSecurity extends Migration
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

        $schema->create('web_securities', function (CustomBlueprint $table) {
            $table->smallInteger('Emp_Level');
            $table->varchar('ProgramName', 40);
            $table->boolean('Allow')->nullable();
            $table->primary(['Emp_Level','ProgramName']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('web_securities');
    }
}
