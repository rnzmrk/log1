<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Supplier;
use App\Models\SupplierPost;
use App\Models\InboundLogistic;
use App\Models\OutboundLogistic;
use App\Models\Inventory;
use App\Models\AssetRequest;
use App\Models\DeliveryConfirmation;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with real data.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get current user
        $user = auth()->user();
        
        // Get real counts for dashboard cards
        $stats = [
            'total_users' => User::count(),
            'active_suppliers' => Supplier::where('status', 'active')->count(),
            'total_suppliers' => Supplier::count(),
            'pending_posts' => SupplierPost::where('status', 'draft')->count(),
            'total_posts' => SupplierPost::count(),
            'published_posts' => SupplierPost::where('status', 'published')->count(),
            'inbound_shipments' => InboundLogistic::count(),
            'pending_inbound' => InboundLogistic::where('status', 'pending')->count(),
            'outbound_shipments' => OutboundLogistic::count(),
            'pending_outbound' => OutboundLogistic::where('status', 'pending')->count(),
            'total_inventory' => Inventory::count(),
            'low_stock_items' => Inventory::where('status', 'Low Stock')->count(),
            'asset_requests' => AssetRequest::count(),
            'pending_asset_requests' => AssetRequest::where('status', 'pending')->count(),
            'delivery_confirmations' => DeliveryConfirmation::count(),
            'pending_deliveries' => DeliveryConfirmation::where('status', 'pending')->count(),
        ];
        
        // Get recent activities
        $recentActivities = $this->getRecentActivities();
        
        // Get chart data for the last 7 days
        $chartData = $this->getChartData();
        
        // Get top suppliers
        $topSuppliers = Supplier::withCount(['supplierPosts' => function($query) {
                $query->where('status', 'published');
            }])
            ->orderBy('supplier_posts_count', 'desc')
            ->limit(5)
            ->get();
        
        // Get inventory alerts
        $inventoryAlerts = Inventory::where('status', 'Low Stock')
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'user',
            'stats', 
            'recentActivities',
            'chartData',
            'topSuppliers',
            'inventoryAlerts'
        ));
    }
    
    private function getRecentActivities()
    {
        $activities = collect();
        
        // Recent supplier posts
        $recentPosts = SupplierPost::with('supplier')
            ->latest()
            ->limit(3)
            ->get();
            
        foreach ($recentPosts as $post) {
            $supplierName = $post->supplier ? $post->supplier->name : 'Unknown Supplier';
            $activities->push([
                'type' => 'post',
                'message' => "New post by {$supplierName}",
                'time' => $post->created_at->diffForHumans(),
                'icon' => 'bx-file',
                'color' => 'blue'
            ]);
        }
        
        // Recent inbound shipments
        $recentInbound = InboundLogistic::latest()
            ->limit(2)
            ->get();
            
        foreach ($recentInbound as $shipment) {
            $activities->push([
                'type' => 'inbound',
                'message' => "Inbound shipment #{$shipment->id} received",
                'time' => $shipment->created_at->diffForHumans(),
                'icon' => 'bx-package',
                'color' => 'green'
            ]);
        }
        
        // Recent outbound shipments
        $recentOutbound = OutboundLogistic::latest()
            ->limit(2)
            ->get();
            
        foreach ($recentOutbound as $shipment) {
            $activities->push([
                'type' => 'outbound',
                'message' => "Outbound shipment #{$shipment->id} dispatched",
                'time' => $shipment->created_at->diffForHumans(),
                'icon' => 'bx-truck',
                'color' => 'purple'
            ]);
        }
        
        return $activities->sortByDesc('time')->take(6)->values();
    }
    
    private function getChartData()
    {
        $days = [];
        $inboundData = [];
        $outboundData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days[] = $date->format('M j');
            
            $inboundData[] = InboundLogistic::whereDate('created_at', $date->format('Y-m-d'))->count();
            $outboundData[] = OutboundLogistic::whereDate('created_at', $date->format('Y-m-d'))->count();
        }
        
        return [
            'labels' => $days,
            'inbound' => $inboundData,
            'outbound' => $outboundData
        ];
    }
}
