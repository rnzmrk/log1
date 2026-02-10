<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InboundLogistic;

class InboundLogisticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample inbound logistics records
        $sampleData = [
            [
                'po_number' => 'PO2026020001',
                'supplier' => 'TechSupply Inc',
                'item_name' => 'Laptop Computers',
                'quantity' => 25,
                'department' => 'IT Department',
                'received_by' => 'John Smith',
                'status' => 'Pending',
                'quality' => 'Pending',
                'expected_date' => now()->addDays(3),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'po_number' => 'PO2026020002',
                'supplier' => 'Office Furniture Co',
                'item_name' => 'Office Chairs',
                'quantity' => 50,
                'department' => 'Operations',
                'received_by' => 'Sarah Johnson',
                'status' => 'In Transit',
                'quality' => 'Pending',
                'expected_date' => now()->addDays(2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'po_number' => 'PO2026020003',
                'supplier' => 'Global Tech Solutions',
                'item_name' => 'Computer Monitors',
                'quantity' => 30,
                'department' => 'Finance Department',
                'received_by' => 'Mike Wilson',
                'status' => 'Delivered',
                'quality' => 'Pass',
                'expected_date' => now()->subDays(1),
                'received_date' => now(),
                'created_at' => now()->subDays(2),
                'updated_at' => now(),
            ],
            [
                'po_number' => 'PO2026020004',
                'supplier' => 'Stationery World',
                'item_name' => 'Printer Paper',
                'quantity' => 200,
                'department' => 'Administration',
                'received_by' => 'Emily Davis',
                'status' => 'Pending',
                'quality' => 'Pending',
                'expected_date' => now()->addDays(5),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($sampleData as $data) {
            InboundLogistic::create($data);
        }

        $this->command->info('Sample inbound logistics data created successfully!');
    }
}
