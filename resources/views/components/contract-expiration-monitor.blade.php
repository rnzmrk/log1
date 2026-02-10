@props(['contracts' => null])

@php
    // If no contracts provided, fetch them
    if ($contracts === null) {
        $contracts = App\Models\Contract::where('status', 'Active')
            ->orWhere('status', 'Under Review')
            ->orderBy('end_date', 'asc')
            ->limit(10)
            ->get();
    }

    $criticalContracts = $contracts->filter(fn($c) => $c->urgency_level === 'critical');
    $highPriorityContracts = $contracts->filter(fn($c) => $c->urgency_level === 'high');
    $needsAttention = $contracts->filter(fn($c) => $c->needs_attention);
@endphp

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Contract Expiration Monitor</h3>
        <div class="flex items-center gap-2">
            @if($criticalContracts->count() > 0)
                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                    {{ $criticalContracts->count() }} Critical
                </span>
            @endif
            @if($highPriorityContracts->count() > 0)
                <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                    {{ $highPriorityContracts->count() }} High Priority
                </span>
            @endif
        </div>  
    </div>

    @if($contracts->count() > 0)
        <div class="space-y-3">
            @foreach($contracts as $contract)
                <div class="flex items-center justify-between p-3 rounded-lg border 
                    @if($contract->urgency_level === 'critical') border-red-200 bg-red-50
                    @elseif($contract->urgency_level === 'high') border-orange-200 bg-orange-50
                    @elseif($contract->urgency_level === 'medium') border-yellow-200 bg-yellow-50
                    @else border-gray-200 bg-gray-50
                    @endif
                ">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <h4 class="text-sm font-medium text-gray-900">{{ $contract->contract_name }}</h4>
                            @if($contract->needs_attention)
                                @if($contract->urgency_level === 'critical')
                                    <i class='bx bx-error-circle text-red-500'></i>
                                @elseif($contract->urgency_level === 'high')
                                    <i class='bx bx-error text-orange-500'></i>
                                @else
                                    <i class='bx bx-info-circle text-yellow-500'></i>
                                @endif
                            @endif
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            Vendor: {{ $contract->vendor }} • Value: ₱{{ number_format($contract->contract_value, 2) }}
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <div class="{{ $contract->days_left_color }} text-sm font-medium">
                            {{ $contract->days_left_text }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $contract->end_date->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="flex items-center justify-between text-xs text-gray-600">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1">
                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                        <span>Critical (≤7 days)</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                        <span>High (≤30 days)</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                        <span>Medium (≤90 days)</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span>Good (>90 days)</span>
                    </div>
                </div>
                <a href="{{ route('admin.procurement.create-contract-reports') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    View All →
                </a>
            </div>
        </div>
    @else
        <div class="text-center py-8">
            <i class='bx bx-calendar-check text-4xl text-gray-300 mb-2'></i>
            <p class="text-gray-500 text-sm">No active contracts to monitor</p>
        </div>
    @endif
</div>

{{-- Automated Refresh Script --}}
<script>
// Auto-refresh the widget every 5 minutes
setInterval(() => {
    // You can add AJAX refresh logic here if needed
    console.log('Contract expiration monitor refreshed');
}, 300000);
</script>
