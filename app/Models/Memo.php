<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'content'];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function scopeOrderByDefault($query)
    {
        return $query->orderBy('id', 'DESC');
    }
}
