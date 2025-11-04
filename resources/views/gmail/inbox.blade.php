@extends('layouts.hr_app')

@section('content')
<div class="container">
    <h1>Gmail Inbox</h1>
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="get" action="{{ route('gmail.inbox') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search (e.g., from:someone subject:invoice)">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <div class="list-group">
        @forelse($messages as $msg)
            <div class="list-group-item">
                <div><strong>{{ $msg['subject'] ?? '(no subject)' }}</strong></div>
                <div>{{ $msg['from'] ?? '' }} — <small>{{ $msg['date'] ?? '' }}</small></div>
                <div class="text-muted">{{ $msg['snippet'] }}</div>
            </div>
        @empty
            <div class="text-muted">No messages found.</div>
        @endforelse
    </div>

   <div class="mt-3 d-flex justify-content-between">
    <a class="btn btn-secondary" href="{{ route('gmail.connect') }}">Reconnect</a>
    
    @if(isset($nextPageToken))
        <a class="btn btn-primary" href="{{ route('gmail.inbox', array_merge(request()->all(), ['pageToken' => $nextPageToken])) }}">
            Next Page →
        </a>
    @endif
</div>
</div>
@endsection


