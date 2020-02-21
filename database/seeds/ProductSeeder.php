<?php

use App\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product')->delete();

        $products = array(
            ['name'=>'Custom Print T-shirt', 'slug'=>'custom-print-t-shirt', 'price'=>320, 'details'=>'Fave pic on your t-shirt', 'description'=>'Send us your favorite picture, and take it as your favorite T-Shirt.',
                'quantity'=>20, 'category_id'=>1, 'size'=>'S/M/L', 'color'=>'White/Black']
        );

        foreach ($products as $item)
        {
            Product::create($item);
        }
    }
}
