<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryUser extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'history_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'url_id',
        'ip_address',
        'user_agent',
    ];
}
