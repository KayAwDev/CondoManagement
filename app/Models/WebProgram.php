<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebProgram extends Model
{
    protected $table = 'web_programs';
    protected $primaryKey = 'ProgramName';
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

    public function WebSecurity()
    {
        return $this->belongsTo(WebSecurity::class, 'ProgramName','ProgramName');
    }


}
