<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    protected $modifiableColumns ;
    protected $listPageColumns;
    protected $otherPageColumns;

    public function __construct() {
        $this->modifiableColumns = (new Task)->getFillable();
        $this->listPageColumns = ['id', 'title', 'category_id', 'description', 'due_date', 'completed_at'];
        $this->otherPageColumns = ['id', 'title', 'category_id', 'description'];
    }

    public function index(Request $request)
    {
        $q = Task::query();

        // ここに絞り込みの処理を追加します。
        // $request->input('search') ? $q->whereNotNull('completed_at') : $q;

        $tasks = $q->with(['category' => fn($query) => $query->select('id', 'name')])
                ->withCount('memos')
                ->select($this->listPageColumns)
                ->orderByDefault()
                ->get();

        $categories = Category::getCategoryList();

        return compact('tasks', 'categories');
    }

    public function show($id)
    {
        $task = Task::with('memos')->select($this->otherPageColumns)->findOrFail($id);
        $categories = Category::getCategoryList();

        return compact('task', 'categories');
    }

    public function store(Request $request)
    {
        $request->merge([
            'title' => '手動タイトル',
            'description' => '手動内容',
            "due_date" => "2023-11-05",
            'category_id' => 1
        ]);

        $data = $request->only($this->modifiableColumns);
        $task = Task::create($data);
        return compact('task');
    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'title' => '更新タイトル',
            'description' => '更新内容',
            "due_date" => "2023-11-05",
            'category_id' => 1
        ]);

        $task = Task::findOrFail($id);
        DB::transaction(function () use ($request, $task) {
            $data = $request->only($this->modifiableColumns);
            $task = $task->update($data);
        });
        return compact('task');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return compact('task');
    }
}
