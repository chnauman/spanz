@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Tender Invitations</h2>
                <a href="{{ route('tenders.index') }}" class="btn btn-outline-primary">Browse All Tenders</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                @forelse($invitations as $invitation)
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title">{{ $invitation->tender->titleHeadline() }}</h5>
                                <span class="badge bg-{{ $invitation->status === 'pending' ? 'warning' : ($invitation->status === 'viewed' ? 'info' : 'success') }}">
                                    {{ ucfirst($invitation->status) }}
                                </span>
                            </div>

                            <p class="card-text text-muted small">
                                Posted by {{ $invitation->tender->user->name }} • {{ $invitation->created_at->diffForHumans() }}
                            </p>

                            <p class="card-text">{{ Str::limit($invitation->tender->description, 150) }}</p>

                            <div class="row text-muted small mb-3">
                                <div class="col-6">
                                    <strong>Category:</strong> {{ $invitation->tender->category->name }}
                                </div>
                                @if($invitation->tender->budget)
                                <div class="col-6">
                                    <strong>Budget:</strong> {{ $invitation->tender->currency }} {{ number_format($invitation->tender->budget, 2) }}
                                </div>
                                @endif
                                <div class="col-6">
                                    <strong>Deadline:</strong> {{ $invitation->tender->deadline->format('M d, Y') }}
                                </div>
                                @if($invitation->tender->displayLocation() !== 'Location not specified')
                                <div class="col-6">
                                    <strong>Location:</strong> {{ $invitation->tender->displayLocation() }}
                                </div>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">
                                    @if($invitation->viewed_at)
                                        Viewed {{ $invitation->viewed_at->diffForHumans() }}
                                    @else
                                        Not viewed yet
                                    @endif
                                </span>
                                <a href="{{ route('tenders.invitation.view', $invitation->id) }}" class="btn btn-primary btn-sm">View Tender</a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-12">
                        <h4 class="text-muted">No tender invitations yet</h4>
                        <p class="text-muted">You'll receive invitations when tenders are posted in categories you're interested in.</p>
                        <a href="{{ route('user.interests') }}" class="btn btn-primary">Update My Interests</a>
                    </div>
                </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center">
                {{ $invitations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
