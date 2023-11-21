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
    protected $sortValidationColumns = ['status', 'category', 'due_date', 'completed_at', 'memos_count'];
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

    // 並び替え
    public function isSortStatus($sortStatus)
    {
        return $sortStatus == 'true' ? 'ASC' : 'DESC';
    }
    public function isSortValidationColumn($columnName)
    {
        return in_array($columnName, $this->sortValidationColumns);
    }

    /*
    Scope
    */
    public function scopeOrderByColumn($query, $sortArrayData)
    {
        $columnName = $sortArrayData[0];
        $sortStatus = $sortArrayData[1];

        $this->isSortValidationColumn($columnName);

        return $query->orderBy($columnName, $this->isSortStatus($sortStatus));
    }

    public function scopeOrderByDefault($query)
    {
        return $query->orderBy('completed_at', 'ASC')->orderBy('due_date', 'ASC')->orderBy('id', 'DESC');
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
