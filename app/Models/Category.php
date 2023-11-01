<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'deleted_at'];

    public static function getCategoryList()
    {
        return self::select('id', 'name')->get();
    }

    public function scopeOrderByDefault($query)
    {
        return $query->orderBy('id', 'DESC');
    }
}
