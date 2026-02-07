<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contract;

class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contracts = [
            [
                'contract_number' => 'CTR-2026-0002',
                'contract_name' => 'Office Supplies Contract',
                'vendor' => 'Office Depot',
                'vendor_contact' => 'John Smith',
                'vendor_email' => 'john@officedepot.com',
                'vendor_phone' => '(555) 123-4567',
                'contract_type' => 'Supply',
                'contract_value' => 25000.00,
                'status' => 'Active',
                'priority' => 'Medium',
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
                'description' => 'Annual office supplies contract for all departments',
                'created_by' => 'Admin User',
            ],
            [
                'contract_number' => 'CTR-2026-0003',
                'contract_name' => 'IT Maintenance Services',
                'vendor' => 'Tech Solutions Inc',
                'vendor_contact' => 'Sarah Johnson',
                'vendor_email' => 'sarah@techsolutions.com',
                'vendor_phone' => '(555) 987-6543',
                'contract_type' => 'Maintenance',
                'contract_value' => 75000.00,
                'status' => 'Active',
                'priority' => 'High',
                'start_date' => '2024-02-01',
                'end_date' => '2025-01-31',
                'description' => 'Comprehensive IT maintenance and support services',
                'created_by' => 'IT Manager',
            ],
            [
                'contract_number' => 'CTR-2026-0004',
                'contract_name' => 'Cleaning Services',
                'vendor' => 'CleanPro Services',
                'vendor_contact' => 'Mike Wilson',
                'vendor_email' => 'mike@cleanpro.com',
                'vendor_phone' => '(555) 456-7890',
                'contract_type' => 'Service',
                'contract_value' => 35000.00,
                'status' => 'Under Review',
                'priority' => 'Medium',
                'start_date' => '2024-03-01',
                'end_date' => '2025-02-28',
                'description' => 'Daily cleaning and maintenance services for office building',
                'created_by' => 'Facilities Manager',
            ],
            [
                'contract_number' => 'CTR-2026-0005',
                'contract_name' => 'Software License Agreement',
                'vendor' => 'Microsoft Corporation',
                'vendor_contact' => 'Enterprise Sales',
                'vendor_email' => 'enterprise@microsoft.com',
                'vendor_phone' => '(555) 234-5678',
                'contract_type' => 'Software License',
                'contract_value' => 120000.00,
                'status' => 'Active',
                'priority' => 'Urgent',
                'start_date' => '2024-01-15',
                'end_date' => '2025-01-14',
                'description' => 'Microsoft Office 365 and Azure services license',
                'created_by' => 'IT Director',
            ],
            [
                'contract_number' => 'CTR-2026-0006',
                'contract_name' => 'Hardware Lease Agreement',
                'vendor' => 'Dell Technologies',
                'vendor_contact' => 'Account Manager',
                'vendor_email' => 'accounts@dell.com',
                'vendor_phone' => '(555) 345-6789',
                'contract_type' => 'Hardware Lease',
                'contract_value' => 85000.00,
                'status' => 'Draft',
                'priority' => 'Low',
                'start_date' => '2024-04-01',
                'end_date' => '2026-03-31',
                'description' => 'Lease agreement for 50 new laptops and workstations',
                'created_by' => 'Procurement Team',
            ],
        ];

        foreach ($contracts as $contract) {
            Contract::create($contract);
        }

        $this->command->info('Sample contracts created successfully!');
    }
}
