<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->bigIncrements('TenantID');
            $table->string('Tenant_Name', 200);
            $table->string('Tenant_ContactNumber', 100);
            $table->bigInteger('Tenant_UnitID');
            $table->foreign('Tenant_UnitID')->references('UnitID')->on('units');
            $table->dateTime('CreatedDateTime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenants');
    }
}
