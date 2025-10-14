@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Active Tenders</h2>
                @auth
                    @if(!auth()->user()->isAdmin())
                        <a href="{{ route('tenders.create') }}" class="btn btn-primary">Post a Tender</a>
                    @endif
                @endauth
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                @forelse($tenders as $tender)
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title">{{ $tender->title }}</h5>
                                <span class="badge bg-primary">{{ $tender->category->name }}</span>
                            </div>
                            
                            <p class="card-text text-muted small">
                                Posted by {{ $tender->user->name }} • {{ $tender->created_at->diffForHumans() }}
                            </p>
                            
                            <p class="card-text">{{ Str::limit($tender->description, 150) }}</p>
                            
                            <div class="row text-muted small mb-3">
                                @if($tender->budget)
                                <div class="col-6">
                                    <strong>Budget:</strong> {{ $tender->currency }} {{ number_format($tender->budget, 2) }}
                                </div>
                                @endif
                                <div class="col-6">
                                    <strong>Deadline:</strong> {{ is_string($tender->deadline) ? \Carbon\Carbon::parse($tender->deadline)->format('M d, Y') : $tender->deadline->format('M d, Y') }}
                                </div>
                                @if($tender->location)
                                <div class="col-12 mt-1">
                                    <strong>Location:</strong> {{ $tender->location }}
                                </div>
                                @endif
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-{{ $tender->isActive() ? 'success' : 'danger' }}">
                                    {{ $tender->isActive() ? 'Active' : 'Expired' }}
                                </span>
                                <a href="{{ route('tenders.detail', $tender->id) }}" class="btn btn-outline-primary btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-12">
                        <h4 class="text-muted">No active tenders found</h4>
                        <p class="text-muted">Check back later for new opportunities.</p>
                    </div>
                </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center">
                {{ $tenders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
