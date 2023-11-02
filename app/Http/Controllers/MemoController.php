<?php

namespace App\Http\Controllers;

use App\Models\Memo;
use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemoController extends Controller
{

    protected $validator;
    protected $modifiableColumns;
    protected $MemoColumns = ['id', 'task_id','content'];

    public function __construct() {
        $this->validator = [
            'content' => 'required',
        ];

        $this->modifiableColumns = (new Memo)->getFillable();
    }

    /*
    show,update,delete処理を共通化
    */
    private function findMemoByTask($memo_id) {
        $memo = Memo::with('task')
                 ->select($this->MemoColumns)
                 ->findOrFail($memo_id);

        return !empty($memo->task) ? $memo : abort(404);
    }

    public function index($task_id)
    {
        $memos = Memo::select($this->MemoColumns)->whereTaskId($task_id)->get();

        return compact('memos');
    }

    public function store($task_id, Request $request)
    {
        $request->validate($this->validator);

        $task = Task::findOrFail($task_id);
        $memo = $task->memos()->create($request->only($this->modifiableColumns));

        return compact('memo');
    }

    public function show($task_id, $memo_id)
    {
        $memo = $this->findMemoByTask($memo_id);

        return compact('memo');
    }

    public function update($task_id, $memo_id, Request $request)
    {
        $request->validate($this->validator);

        $memo = $this->findMemoByTask($memo_id);
        DB::transaction(function () use ($request, $memo) {
            $data = $request->only($this->modifiableColumns);
            $memo->update($data);
        });

        return compact('memo');
    }

    public function destroy($task_id, Memo $memo)
    {
        $memo->delete();

        return compact('memo');
    }
}
