<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BorrowRequest;
use App\Models\Equipment;
use App\Models\User;

class BorrowRequestSeeder extends Seeder {
    public function run(): void {
        $student = User::where('role','student')->first();
        $equipment = Equipment::all();
        
        // Check natin kung may student at equipment bago mag-create
        if ($student && $equipment->count() > 0) {
            foreach (range(1, 5) as $m) {
                BorrowRequest::create([
                    'user_id' => $student->id, 
                    'equipment_id' => $equipment->random()->id,
                    'quantity' => 1, 
                    'purpose' => 'Class activity',
                    'borrow_date' => now()->subMonths(5 - $m), 
                    'due_date' => now()->subMonths(5 - $m)->addDays(3),
                    'status' => 'pending',
                ]);
            }
        }
    }
}