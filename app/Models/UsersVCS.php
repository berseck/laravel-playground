<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsersVCS extends Model
{
    use SoftDeletes;

    protected $table = "users_vcs";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array('user_id','price');

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

    public function users()
    {
        return $this->belongsTo("\App\Users", "user_id");
    }
}
