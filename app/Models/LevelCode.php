<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelCode extends Model
{
    protected $table = 'level_codes';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable   = ['LevelCode',
                             'LevelDesc'
                            ];

    public function Employees()
    {
        return $this->belongsTo(Employees::class, 'LevelCode', 'Emp_Level');
    }

}
