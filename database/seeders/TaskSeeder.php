<?php

namespace Database\Seeders;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $records = [
                [
                    'id' => '1',
                    'category_id' => '1',
                    'title' => 'テストタイトル１',
                    'description' => '内容１',
                    'due_date' => '2023-11-5 00:00:00',
                    'completed_at' => null,
                    'deleted_at' => null,
                    'created_at' => new Carbon('now'),
                    'updated_at' => new Carbon('now'),
                ],
                [
                    'id' => '2',
                    'category_id' => '2',
                    'title' => 'テストタイトル２',
                    'description' => '内容２',
                    'due_date' => '2023-11-6 00:00:00',
                    'completed_at' => null,
                    'deleted_at' => null,
                    'created_at' => new Carbon('now'),
                    'updated_at' => new Carbon('now'),
                ],
            ];

            Schema::disableForeignKeyConstraints();
            Task::truncate();
            Task::insert($records);
            Schema::enableForeignKeyConstraints();
    }
}
