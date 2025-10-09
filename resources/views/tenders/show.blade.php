@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ $tender->title }}</h4>
                        <span class="badge bg-{{ $tender->isActive() ? 'success' : 'danger' }}">
                            {{ $tender->isActive() ? 'Active' : 'Expired' }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Category:</strong> {{ $tender->category->name }}
                        </div>
                        <div class="col-md-6">
                            <strong>Posted by:</strong> {{ $tender->user->name }}
                        </div>
                    </div>

                    @if($tender->budget)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Budget:</strong> {{ $tender->currency }} {{ number_format($tender->budget, 2) }}
                        </div>
                        <div class="col-md-6">
                            <strong>Deadline:</strong> {{ $tender->deadline->format('M d, Y') }}
                        </div>
                    </div>
                    @else
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Deadline:</strong> {{ $tender->deadline->format('M d, Y') }}
                        </div>
                    </div>
                    @endif

                    @if($tender->location)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Location:</strong> {{ $tender->location }}
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <strong>Description:</strong>
                        <p class="mt-2">{{ $tender->description }}</p>
                    </div>

                    @if($tender->requirements)
                    <div class="mb-3">
                        <strong>Requirements:</strong>
                        <p class="mt-2">{{ $tender->requirements }}</p>
                    </div>
                    @endif

                    @if($tender->contact_email || $tender->contact_phone)
                    <div class="mb-3">
                        <strong>Contact Information:</strong>
                        <div class="mt-2">
                            @if($tender->contact_email)
                            <p><strong>Email:</strong> <a href="mailto:{{ $tender->contact_email }}">{{ $tender->contact_email }}</a></p>
                            @endif
                            @if($tender->contact_phone)
                            <p><strong>Phone:</strong> {{ $tender->contact_phone }}</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="text-muted small">
                        <p>Posted {{ $tender->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tender Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Status:</strong>
                        <span class="badge bg-{{ $tender->isActive() ? 'success' : 'danger' }} ms-2">
                            {{ $tender->isActive() ? 'Active' : 'Expired' }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Days Remaining:</strong>
                        <span class="ms-2">
                            @if($tender->deadline > now())
                                {{ $tender->deadline->diffInDays(now()) }} days
                            @else
                                Expired
                            @endif
                        </span>
                    </div>

                    @auth
                        @if(auth()->user()->id === $tender->user_id)
                        <div class="d-grid gap-2">
                            <a href="{{ route('tenders.my-tenders') }}" class="btn btn-outline-primary">My Tenders</a>
                        </div>
                        @else
                        <div class="d-grid gap-2">
                            <a href="mailto:{{ $tender->contact_email ?? $tender->user->email }}" class="btn btn-primary">Contact Buyer</a>
                        </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
