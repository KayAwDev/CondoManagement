<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    protected $table = 'visitor_logs';
    protected $primaryKey = 'VisitorLogID';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable   = ['Visitor_Name',
                             'Visitor_ContactNumber',
                             'Visitor_NRIC',
                             'VisitPlace',
                             'Visit_UnitID',
                             'EnterDateTime',
                             'ExitDateTime'
                            ];

    public function Units()
    {
        return $this->hasOne(Unit::class);
    }
}
