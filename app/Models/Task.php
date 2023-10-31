<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['title', 'category_id', 'description', 'due_date', 'completed_at', 'deleted_at'];
    protected $casts=['completed_at' => 'date'];
    protected $dates = ['deleted_at'];


    /*
    リレーション
    */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function memos()
    {
        return $this->hasMany(Memo::class);
    }

    public function scopeOrderByDefault($query)
    {
        return $query->orderBy('completed_at', 'DESC')->orderBy('id', 'DESC');
    }

}
