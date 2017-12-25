<?php

namespace App\Models;

use Library\Model;

class Task extends Model
{
    protected $table = 'tasks';
    protected $fillable = [
        'name',
        'email',
        'text',
        'image',
        'done',
        'created_at',
        'updated_at'
    ];
}
