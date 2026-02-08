{{-- Admin Notifications System --}}
<script src="{{ asset('js/admin-notifications.js') }}"></script>

{{-- Flash Messages for Notification System --}}
@if(session('success'))
    <div data-success="{{ session('success') }}" class="hidden"></div>
@endif

@if(session('error'))
    <div data-error="{{ session('error') }}" class="hidden"></div>
@endif

@if(session('warning'))
    <div data-warning="{{ session('warning') }}" class="hidden"></div>
@endif

@if(session('info'))
    <div data-info="{{ session('info') }}" class="hidden"></div>
@endif

{{-- Validation Errors --}}
@if($errors->any())
    <div data-error="Please correct the following errors:
    @foreach($errors->all() as $error)
        • {{ $error }}
    @endforeach" class="hidden"></div>
@endif
