<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    protected $validator;
    protected $modifiableColumns;
    protected $listPageColumns = ['id', 'title', 'category_id', 'description', 'due_date', 'completed_at'];
    protected $otherPageColumns = ['id', 'title', 'category_id', 'description'];

    public function __construct() {
        $this->validator = [
            'category_id' => 'required|exists:categories,id',
            'title' => 'required',
        ];

        $this->modifiableColumns = (new Task)->getFillable();
    }

    public function index(Request $request)
    {
        $q = Task::query();

        $request->input('keyword') ? $q->word($request->input('keyword')) : $q;
        $request->input('category') ? $q->whereIn('category_id', $request->input('category')) : $q;
        $request->input('status') == '未完了' ? $q->whereNull('completed_at') : $q;
        $request->input('status') == '完了' ? $q->whereNotNull('completed_at') : $q;

        $tasks = $q->with(['category:id,name'])
                ->select($this->listPageColumns)
                ->withCount('memos')
                ->orderByDefault()
                ->get();

        $categories = Category::getCategoryList();

        return compact('tasks', 'categories');
    }

    public function store(Request $request)
    {
        $request->validate($this->validator);

        $data = $request->only($this->modifiableColumns);
        $task = Task::create($data);

        return compact('task');
    }

    public function show($id)
    {
        $task = Task::with('memos')->select($this->otherPageColumns)->findOrFail($id);
        $categories = Category::getCategoryList();

        return compact('task', 'categories');
    }

    public function update(Request $request, $id)
    {

        $request->validate($this->validator);

        $task = Task::findOrFail($id);
        DB::transaction(function () use ($request, $task) {
            $data = $request->only($this->modifiableColumns);
            $task->update($data);
        });

        return compact('task');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return compact('task');
    }
}
