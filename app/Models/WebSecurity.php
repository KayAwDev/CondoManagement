<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebSecurity extends Model
{
    protected $table = 'web_securities';
    protected $primaryKey = 'Emp_Level';
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

    public function WebProgram()
    {
        return $this->hasOne(WebProgram::class, 'ProgramName','ProgramName');
    }

    public function Employees()
    {
        return $this->belongsToMany(Employees::class, 'Emp_Level', 'Emp_Level');
    }

}
