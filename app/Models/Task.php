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
    protected $casts = ['completed_at' => 'date:Y/m/d', 'due_date' => 'date:Y-m-d'];
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

    public function complete()
    {
        $this->completed_at = now();
        $this->save();
    }

    public function cancel()
    {
        $this->completed_at = null;
        $this->save();
    }

    public function scopeOrderByDefault($query)
    {
        return $query->orderBy('completed_at', 'DESC')->orderBy('id', 'DESC');
    }

    public function scopeWord($query, $text)
    {
        if (!empty($text)) {
            $text = '%'.addcslashes($text, '%_\\').'%';
            $query->where(function ($query) use ($text) {
                $query->orWhere('title', 'like', $text);
                $query->orWhere('description', 'like', $text);
            });
        }
        return $query;
    }

}
