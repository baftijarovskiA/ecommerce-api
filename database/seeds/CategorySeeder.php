<?php

use App\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('category')->delete();

        $category = array(
            ['name'=>'T-Shirts', 'slug'=>'t-shirts']
        );

        foreach ($category as $item)
        {
            Category::create($item);
        }
    }
}
