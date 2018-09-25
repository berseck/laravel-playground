<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Types extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array('name');

    /**
     * Default Timestamps
     *
     * @var boolean
     */
    public $timestamps = true;

    /**
     * Hidden atributes
     *
     * @var array
     */
    protected $hidden = array("created_at", "updated_at", "deleted_at");


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ["created_at", "updated_at", "deleted_at"];
}
