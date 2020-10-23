<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiAuth extends Model
{
    protected $table = 'api_auths';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable   = ['username',
                             'apiKey',
                             'createdAt'
                            ];

    public function Employees()
    {
        return $this->belongsTo(Employees::class, 'username','Emp_Username');
    }
}
