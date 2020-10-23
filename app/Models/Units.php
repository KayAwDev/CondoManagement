<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Units extends Model
{
    protected $table = 'units';
    protected $primaryKey = 'UnitID';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable   = ['Block',
                             'UnitNumber',
                             'UnitOwner',
                             'Owner_ContactNumber',
                             'CreatedDateTime'
                            ];

    public function Tenants()
    {
        return $this->hasMany('App\Models\Tenants','Tenants_UnitID', 'UnitID');
    }

    public function VisitorLog()
    {
        return $this->hasMany('App\Models\VisitorLog','Visit_UnitID', 'UnitID');
    }

}
