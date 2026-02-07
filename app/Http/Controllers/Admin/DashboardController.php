<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // You can add dashboard data here
        $stats = [
            'total_stock_items' => 141,
            'low_stock_alerts' => 1,
            'incoming_shipments' => 4,
            'outgoing_shipments' => 34,
        ];

        return view('admin.dashboard.index', compact('stats'));
    }
}
