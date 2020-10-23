<?php

namespace App\Schemas\Blueprints;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

class CustomBlueprint extends Blueprint
{
    public function varchar($column, $length = null)
    {
        $length = $length ? : Builder::$defaultStringLength;

        return $this->addColumn('varchar', $column, compact('length'));
    }
}
