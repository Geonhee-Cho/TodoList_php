<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Memo extends Model
{
    use SoftDeletes;

    protected $table = 'memos';
    protected $fillable = ['user_id_no','event_date','event_time','title', 'content'];
    protected $dates = ['deleted_at'];

    public $timestamps = true;
}
