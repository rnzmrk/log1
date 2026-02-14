@extends('layouts.app')

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Breadcrumbs -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600 inline-flex items-center">
                    <i class='bx bx-home text-xl mr-2'></i>
                    Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="#" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Document Tracking</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <a href="{{ route('admin.documenttracking.create-document-reports') }}" class="text-gray-700 hover:text-blue-600 ml-1 md:ml-2">Contracts & Reports</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class='bx bx-chevron-right text-gray-400'></i>
                    <span class="ml-1 text-gray-500 md:ml-2">Contract Details</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Contract Details</h1>
            <p class="text-gray-600 mt-1">View complete contract information and related documents</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.documenttracking.create-document-reports') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-arrow-back text-xl'></i>
                Back to Contracts
            </a>
            <a href="{{ route('contracts.edit', $contract->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-edit text-xl'></i>
                Edit Contract
            </a>
        </div>
    </div>

    <!-- Success Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <i class='bx bx-check-circle text-xl mr-2'></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Contract Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Contract Details Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Contract Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Contract Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contract Number</label>
                            <p class="text-gray-900 font-medium">{{ $contract->contract_number }}</p>
                        </div>

                        <!-- Contract Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contract Name</label>
                            <p class="text-gray-900 font-medium">{{ $contract->contract_name }}</p>
                        </div>

                        <!-- Vendor -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vendor</label>
                            <p class="text-gray-900 font-medium">{{ $contract->vendor }}</p>
                            @if ($contract->vendor_contact)
                                <p class="text-sm text-gray-500 mt-1">{{ $contract->vendor_contact }}</p>
                            @endif
                        </div>

                        <!-- Contract Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contract Type</label>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $contract->contract_type }}
                            </span>
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                            <p class="text-gray-900 font-medium">{{ $contract->start_date->format('F d, Y') }}</p>
                        </div>

                        <!-- End Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                            <p class="text-gray-900 font-medium">{{ $contract->end_date->format('F d, Y') }}</p>
                        </div>

                        <!-- Contract Value -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contract Value</label>
                            <p class="text-gray-900 font-medium">
                                @if ($contract->contract_value)
                                    ₱{{ number_format($contract->contract_value, 2) }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <!-- Duration -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                            <p class="text-gray-900 font-medium">{{ $contract->start_date->diffInDays($contract->end_date) }} days</p>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            @if ($contract->status === 'Draft')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Draft
                                </span>
                            @elseif ($contract->status === 'Under Review')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    Under Review
                                </span>
                            @elseif ($contract->status === 'Active')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @elseif ($contract->status === 'Expired')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Expired
                                </span>
                            @elseif ($contract->status === 'Terminated')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Terminated
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                    {{ $contract->status }}
                                </span>
                            @endif
                        </div>

                        <!-- Priority -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                            @if ($contract->priority === 'Urgent')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Urgent
                                </span>
                            @elseif ($contract->priority === 'High')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    High
                                </span>
                            @elseif ($contract->priority === 'Medium')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Medium
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Low
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Description -->
                    @if ($contract->description)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <p class="text-gray-900">{{ $contract->description }}</p>
                        </div>
                    @endif

                    <!-- Terms and Conditions -->
                    @if ($contract->terms_and_conditions)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Terms and Conditions</label>
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $contract->terms_and_conditions }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Related Documents -->
            @if ($relatedDocuments->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Related Documents</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach ($relatedDocuments as $document)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="bg-blue-100 rounded-full p-2 mr-3">
                                            <i class='bx bx-file text-blue-600 text-sm'></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $document->document_title }}</p>
                                            <p class="text-sm text-gray-500">{{ $document->document_type }} • {{ $document->upload_date->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('uploaded-documents.download', $document->id) }}" class="text-blue-600 hover:text-blue-800" title="Download">
                                        <i class='bx bx-download text-lg'></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Contract Status Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Contract Status</h2>
                </div>
                <div class="p-6">
                    <!-- Days Left -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Time Remaining</label>
                        @php
                            // Calculate days left dynamically - identical to procurement component
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

                            <span class="{{ $colorClass }} text-sm font-medium">
                                {{ $daysLeft }}
                            </span>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contract Progress</label>
                        @php
                            $totalDays = $contract->start_date->diffInDays($contract->end_date);
                            $elapsedDays = $contract->start_date->diffInDays(now());
                            $progress = min(max(($elapsedDays / $totalDays) * 100, 0), 100);
                        @endphp
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ round($progress) }}% completed</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-2">
                        @if ($contract->status === 'Active')
                            <button onclick="openRenewModal({{ $contract->id }})" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                <i class='bx bx-refresh mr-2'></i>
                                Renew Contract
                            </button>
                        @endif
                        @if (in_array($contract->status, ['Active', 'Expired']))
                            <button onclick="openTerminateModal({{ $contract->id }})" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                <i class='bx bx-x-circle mr-2'></i>
                                Terminate Contract
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Quick Info</h2>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Created</span>
                        <span class="text-sm font-medium text-gray-900">{{ $contract->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Last Updated</span>
                        <span class="text-sm font-medium text-gray-900">{{ $contract->updated_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Created By</span>
                        <span class="text-sm font-medium text-gray-900">{{ $contract->created_by ?? 'System' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Renew Modal -->
    <div id="renewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Renew Contract</h3>
                <form id="renewForm" method="POST">
                    @csrf
                    <input type="hidden" name="contract_id" id="renewContractId">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">New End Date</label>
                        <input type="date" name="new_end_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Renewal Terms (Optional)</label>
                        <textarea name="renewal_terms" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeRenewModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg">
                            Cancel
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                            Renew Contract
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Terminate Modal -->
    <div id="terminateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Terminate Contract</h3>
                <form id="terminateForm" method="POST">
                    @csrf
                    <input type="hidden" name="contract_id" id="terminateContractId">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Termination Reason</label>
                        <textarea name="termination_reason" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Termination Date</label>
                        <input type="date" name="termination_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeTerminateModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg">
                            Cancel
                        </button>
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg">
                            Terminate Contract
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
function openRenewModal(contractId) {
    document.getElementById('renewContractId').value = contractId;
    document.getElementById('renewModal').classList.remove('hidden');
}

function closeRenewModal() {
    document.getElementById('renewModal').classList.add('hidden');
}

function openTerminateModal(contractId) {
    document.getElementById('terminateContractId').value = contractId;
    document.getElementById('terminateModal').classList.remove('hidden');
}

function closeTerminateModal() {
    document.getElementById('terminateModal').classList.add('hidden');
}
</script>
