<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    protected $table = 'employees';
    protected $primaryKey = 'Emp_Username';
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable   = ['Emp_Username',
                             'Emp_Password',
                             'Emp_Name',
                             'Emp_Level',
                             'CreatedDateTime'
                            ];


    public function WebSecurity()
    {
        return $this->hasMany(WebSecurity::class, 'Emp_Level', 'Emp_Level');
    }

    public function LevelCode()
    {
        return $this->hasOne(LevelCode::class, 'LevelCode', 'Emp_Level');
    }

    public function ApiAuth()
    {
        return $this->hasMany(ApiAuth::class, 'Username', 'Emp_Username');
    }
}
