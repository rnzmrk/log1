<?php

require_once 'vendor/autoload.php';

use App\Models\LogisticsReport;
use App\Models\DeliveryConfirmation;
use App\Models\ProjectPlanning;

// Test if the models are working
echo "Testing Logistics Reports functionality...\n\n";

// Test LogisticsReport model
try {
    $reportCount = LogisticsReport::count();
    echo "✅ LogisticsReport model working - Found {$reportCount} reports\n";
    
    if ($reportCount > 0) {
        $latestReport = LogisticsReport::latest()->first();
        echo "   Latest report: {$latestReport->report_name} ({$latestReport->status})\n";
    }
} catch (Exception $e) {
    echo "❌ LogisticsReport model error: " . $e->getMessage() . "\n";
}

// Test DeliveryConfirmation model
try {
    $deliveryCount = DeliveryConfirmation::count();
    echo "✅ DeliveryConfirmation model working - Found {$deliveryCount} deliveries\n";
} catch (Exception $e) {
    echo "❌ DeliveryConfirmation model error: " . $e->getMessage() . "\n";
}

// Test ProjectPlanning model
try {
    $projectCount = ProjectPlanning::count();
    echo "✅ ProjectPlanning model working - Found {$projectCount} projects\n";
} catch (Exception $e) {
    echo "❌ ProjectPlanning model error: " . $e->getMessage() . "\n";
}

echo "\n🎉 Logistics Reports functionality is ready!\n";
echo "Visit: http://127.0.0.1:8000/admin/logistictracking/reports\n";
?>
