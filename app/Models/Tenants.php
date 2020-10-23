<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenants extends Model
{
    protected $table = 'tenants';
    protected $primaryKey = 'TenantID';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable   = ['Tenant_Name',
                             'Tenant_ContactNumber',
                             'Tenant_UnitID',
                             'CreatedDateTime'
                            ];

    /**
     * Get the unit that owns the tenant.
     */
    public function Units()
    {
        return $this->belongsTo('App\Models\Units', 'Tenants_UnitID');
    }

}
