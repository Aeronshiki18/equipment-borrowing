<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Category;
class CategorySeeder extends Seeder {
    public function run(): void {
        Category::insert([
            ['name'=>'Audio-Visual','description'=>'Projectors, speakers','created_at'=>now(),'updated_at'=>now()],
            ['name'=>'Computers','description'=>'Laptops, peripherals','created_at'=>now(),'updated_at'=>now()],
            ['name'=>'Sports','description'=>'PE equipment','created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}