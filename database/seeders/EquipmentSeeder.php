<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Equipment;
class EquipmentSeeder extends Seeder {
    public function run(): void {
        $av = Category::where('name','Audio-Visual')->value('id');
        $pc = Category::where('name','Computers')->value('id');
        Equipment::insert([
            ['category_id'=>$av,'name'=>'Epson Projector','code'=>'AV-001','total_quantity'=>3,'condition'=>'good','status'=>'active','created_at'=>now(),'updated_at'=>now()],
            ['category_id'=>$pc,'name'=>'Dell Laptop','code'=>'PC-001','total_quantity'=>5,'condition'=>'good','status'=>'active','created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}