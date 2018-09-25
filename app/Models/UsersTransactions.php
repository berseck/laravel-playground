<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsersTransactions extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array('amount', 'from', 'to', 'type', 'flag', 'group_id');

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
    protected $hidden = array("updated_at", "deleted_at");


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ["created_at", "updated_at", "deleted_at"];

    /**
     * Appends attributes
     *
     * @var array
     */
    protected $appends = [
        "from_user",
        "to_user",
    ];

    public function from()
    {
        return $this->belongsTo('\App\User', 'from');
    }

    public function to()
    {
        return $this->belongsTo('\App\User', 'to');
    }

    public function getFromUserAttribute()
    {
        return $this->from()->pluck('email')->first();
    }

    public function getToUserAttribute()
    {
        return $this->to()->pluck('email')->first();
    }
}
