@extends('layouts.app')

@section('content')
<style>
    .thomas-tenders-wrap {
        max-width: 1180px;
        margin: 0 auto;
        padding: 24px 16px 36px;
        color: #15314c;
    }

    .thomas-tenders-header {
        background: #fff;
        border: 1px solid #d8e2ee;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(3, 39, 71, 0.06);
        padding: 16px 18px;
        margin-bottom: 16px;
    }

    .thomas-tender-card {
        background: #fff;
        border: 1px solid #d8e2ee;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(3, 39, 71, 0.06);
        height: 100%;
    }

    .thomas-post-btn {
        background: #0d6efd;
        color: #fff;
        border: 1px solid #0d6efd;
    }

    .thomas-post-btn:hover {
        background: #0b5fd7;
        border-color: #0b5fd7;
        color: #fff;
    }
</style>

<div class="thomas-tenders-wrap">
    <div class="row">
        <div class="col-12">
            <div class="thomas-tenders-header d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0 fw-bold">Active Tenders</h2>
                @auth
                    @if(!auth()->user()->isAdmin())
                        <a href="{{ route('tenders.create') }}" class="btn thomas-post-btn">Post a Tender</a>
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
                    <div class="thomas-tender-card card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-semibold">{{ $tender->title }}</h5>
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
