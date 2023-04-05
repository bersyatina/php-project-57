<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'label_id',
        'task_id'
    ];
    public $table = 'label_task';
    public $timestamps = false;
}
