<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    protected $validator;
    protected $modifiableColumns;
    protected $listPageColumns = ['id', 'name'];
    protected $otherPageColumns = ['id', 'name', 'deleted_at'];

    public function __construct() {
        $this->modifiableColumns = (new Category)->getFillable();

        $this->validator = [
            'name' => 'required',
        ];
    }

    public function index(Request $request)
    {
        $categories = Category::select($this->listPageColumns)->get();

        return compact('categories');
    }

    public function show($id)
    {
        $category = Category::select($this->otherPageColumns)->findOrFail($id);

        return compact('category');
    }

    public function store(Request $request)
    {
        // 動作検証用
        $request->merge([
            'name' => 'カテゴリ名',
        ]);

        $data = $request->only($this->modifiableColumns);
        $category = Category::create($data);

        return compact('category');
    }

    public function update(Request $request, $id)
    {
        // 動作検証用
        $request->merge([
            'name' => '更新カテゴリ名',
        ]);

        $category = Category::findOrFail($id);
        DB::transaction(function () use ($request, $category) {
            $data = $request->only($this->modifiableColumns);
            $category = $category->update($data);
        });

        return compact('category');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return compact('category');
    }
}
