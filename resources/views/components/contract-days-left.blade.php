@props(['contract'])

@php
    // Calculate days left dynamically
    if (!$contract->end_date) {
        $daysLeft = 'N/A';
        $colorClass = 'text-gray-500';
        $urgencyLevel = 'low';
        $needsAttention = false;
        $daysUntilExpiration = null;
    } else {
        $daysUntilExpiration = now()->diffInDays($contract->end_date, false);
        
        if ($daysUntilExpiration < 0) {
            $absDays = abs($daysUntilExpiration);
            $daysLeft = "Expired {$absDays} day" . ($absDays !== 1 ? 's' : '') . " ago";
            $colorClass = 'text-red-600 font-semibold';
            $urgencyLevel = 'critical';
            $needsAttention = true;
        } elseif ($daysUntilExpiration === 0) {
            $daysLeft = 'Expires Today';
            $colorClass = 'text-red-600 font-semibold';
            $urgencyLevel = 'critical';
            $needsAttention = true;
        } elseif ($daysUntilExpiration === 1) {
            $daysLeft = 'Expires Tomorrow';
            $colorClass = 'text-red-600 font-semibold';
            $urgencyLevel = 'critical';
            $needsAttention = true;
        } elseif ($daysUntilExpiration <= 7) {
            $daysLeft = "{$daysUntilExpiration} days left";
            $colorClass = 'text-red-600 font-semibold';
            $urgencyLevel = 'critical';
            $needsAttention = true;
        } elseif ($daysUntilExpiration <= 30) {
            $weeks = floor($daysUntilExpiration / 7);
            $remainingDays = $daysUntilExpiration % 7;
            $daysLeft = "{$weeks} week" . ($weeks !== 1 ? 's' : '');
            if ($remainingDays > 0) {
                $daysLeft .= " {$remainingDays} day" . ($remainingDays !== 1 ? 's' : '');
            }
            $daysLeft .= " left";
            $colorClass = 'text-orange-600 font-semibold';
            $urgencyLevel = 'high';
            $needsAttention = true;
        } elseif ($daysUntilExpiration <= 90) {
            $months = floor($daysUntilExpiration / 30);
            $remainingDays = $daysUntilExpiration % 30;
            $daysLeft = "{$months} month" . ($months !== 1 ? 's' : '');
            if ($remainingDays > 0 && $remainingDays <= 7) {
                $daysLeft .= " {$remainingDays} day" . ($remainingDays !== 1 ? 's' : '');
            }
            $daysLeft .= " left";
            $colorClass = 'text-yellow-600';
            $urgencyLevel = 'medium';
            $needsAttention = false;
        } else {
            $months = floor($daysUntilExpiration / 30);
            $daysLeft = "{$months}+ month" . ($months !== 1 ? 's' : '') . " left";
            $colorClass = 'text-green-600';
            $urgencyLevel = 'low';
            $needsAttention = false;
        }
    }
@endphp

<div class="flex items-center gap-2">
    {{-- Urgency Indicator --}}
    @if($needsAttention)
        @if($urgencyLevel === 'critical')
            <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
        @elseif($urgencyLevel === 'high')
            <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
        @else
            <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
        @endif
    @else
        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
    @endif

    {{-- Clickable Days Left Text --}}
    <a href="{{ route('contracts.show', $contract->id) }}" 
       class="{{ $colorClass }} text-sm hover:underline cursor-pointer"
       title="Click to view contract details">
        {{ $daysLeft }}
    </a>

    {{-- Additional Info for Critical Cases --}}
    @if($urgencyLevel === 'critical' && $daysUntilExpiration !== null)
        @if($daysUntilExpiration < 0)
            <span class="text-xs text-red-500 bg-red-50 px-2 py-1 rounded-full">
                <i class='bx bx-error-alt'></i> Expired
            </span>
        @elseif($daysUntilExpiration <= 7)
            <span class="text-xs text-orange-500 bg-orange-50 px-2 py-1 rounded-full">
                <i class='bx bx-time-five'></i> Action Required
            </span>
        @endif
    @endif
</div>

{{-- Enhanced Tooltip with more details --}}
<div class="relative group inline-block">
    <i class='bx bx-info-circle text-gray-400 text-xs cursor-help'></i>
    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden group-hover:block bg-gray-800 text-white text-xs rounded-lg p-3 whitespace-nowrap z-10" style="min-width: 200px;">
        <div class="font-semibold mb-2">Contract Details</div>
        <div class="space-y-1">
            <div><strong>Contract:</strong> {{ $contract->contract_name }}</div>
            <div><strong>End Date:</strong> {{ $contract->end_date ? $contract->end_date->format('M d, Y') : 'N/A' }}</div>
            <div><strong>Duration:</strong> {{ $contract->duration ?? 'N/A' }} days</div>
            <div><strong>Status:</strong> {{ $contract->status }}</div>
            @if($daysUntilExpiration !== null)
                <div><strong>Days Left:</strong> 
                    <span class="{{ $colorClass }}">
                        {{ $daysUntilExpiration < 0 ? abs($daysUntilExpiration) . ' days overdue' : $daysUntilExpiration . ' days remaining' }}
                    </span>
                </div>
            @endif
            <div class="pt-2 border-t border-gray-600">
                <small><i class='bx bx-click'></i> Click days left to view full details</small>
            </div>
        </div>
        <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-800"></div>
    </div>
</div>
