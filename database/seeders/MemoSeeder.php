<?php

namespace Database\Seeders;

use App\Models\Memo;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class MemoSeeder extends Seeder
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
                    'task_id' => '1',
                    'content' => 'コンテンツ１',
                    'created_at' => new Carbon('now'),
                    'updated_at' => new Carbon('now'),
                ],
                [
                    'id' => '2',
                    'task_id' => '1',
                    'content' => 'コンテンツ２',
                    'created_at' => new Carbon('now'),
                    'updated_at' => new Carbon('now'),
                ],
            ];

            Schema::disableForeignKeyConstraints();
            Memo::truncate();
            Memo::insert($records);
            Schema::enableForeignKeyConstraints();
    }
}
