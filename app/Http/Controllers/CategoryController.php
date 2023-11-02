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
    protected $CategoryColumns = ['id', 'name'];

    public function __construct() {
        $this->validator = [
            'name' => 'required',
        ];

        $this->modifiableColumns = (new Category)->getFillable();
    }

    public function index(Request $request)
    {
        $categories = Category::select($this->CategoryColumns)->get();

        return compact('categories');
    }

    public function store(Request $request)
    {
        $request->validate($this->validator);

        $data = $request->only($this->modifiableColumns);
        $category = Category::create($data);

        return compact('category');
    }

    public function show($id)
    {
        $category = Category::select($this->CategoryColumns)->findOrFail($id);

        return compact('category');
    }

    public function update(Request $request, $id)
    {
        $request->validate($this->validator);

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
