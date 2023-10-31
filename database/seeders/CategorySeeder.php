<?php

namespace Database\Seeders;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CategorySeeder extends Seeder
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
                    'name' => '仕事',
                    'deleted_at' => null,
                    'created_at' => new Carbon('now'),
                    'updated_at' => new Carbon('now'),
                ],
            ];

            Schema::disableForeignKeyConstraints();
            Category::truncate();
            Category::insert($records);
            Schema::enableForeignKeyConstraints();
    }
}
