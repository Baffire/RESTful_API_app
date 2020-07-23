<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    Protected $fillable = [
        'title',
        'question',
        'poll_id'
    ];
}
