@extends('layouts.app')

@section('title', 'Game Details - ZOTA Admin')
@section('page-title', 'Game Details')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-gamepad me-2 text-primary"></i>{{ $game->name }}
                    </h5>
                    <div>
                        @if($game->is_featured)
                            <span class="badge bg-warning text-dark me-2">Featured</span>
                        @endif
                        @if($game->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($game->cover_image)
                <div class="text-center mb-4">
                    <img src="{{ $game->cover_image_url }}" alt="{{ $game->name }}" 
                         class="img-fluid rounded" style="max-height: 300px;">
                </div>
                @endif

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Category</h6>
                        <p class="text-muted">{{ $game->category->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Price</h6>
                        <p class="text-success fw-bold fs-5">Rp {{ number_format($game->price, 0, ',', '.') }}</p>
                    </div>
                </div>

                @if($game->description)
                <div class="mb-4">
                    <h6>Description</h6>
                    <p class="text-muted">{{ $game->description }}</p>
                </div>
                @endif

                @if($game->screenshots)
                <div class="mb-4">
                    <h6>Screenshots</h6>
                    <div class="row">
                        @foreach($game->screenshots_url as $screenshot)
                        <div class="col-md-4 mb-3">
                            <img src="{{ $screenshot }}" alt="Screenshot" 
                                 class="img-thumbnail w-100" data-bs-toggle="modal" data-bs-target="#screenshotModal" 
                                 data-screenshot="{{ $screenshot }}" style="cursor: pointer;">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="d-flex gap-2">
                    <a href="{{ route('games.edit', $game->id) }}" class="btn btn-warning btn-modern">
                        <i class="fas fa-edit me-2"></i>Edit Game
                    </a>
                    <a href="{{ route('games.index') }}" class="btn btn-secondary btn-modern">
                        <i class="fas fa-arrow-left me-2"></i>Back to Games
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2 text-info"></i>Game Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <h3 class="text-primary">{{ $game->transactions_count }}</h3>
                    <small class="text-muted">Total Sales</small>
                </div>
                <div class="text-center mb-3">
                    <h3 class="text-success">Rp {{ number_format($game->transactions_count * $game->price, 0, ',', '.') }}</h3>
                    <small class="text-muted">Total Revenue</small>
                </div>
                <div class="text-center">
                    <small class="text-muted">Created: {{ $game->created_at->format('d M Y') }}</small>
                </div>
            </div>
        </div>

        <!-- Recent Buyers -->
        @if($game->transactions->count() > 0)
        <div class="card mt-4">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-users me-2 text-success"></i>Recent Buyers
                </h5>
            </div>
            <div class="card-body">
                @foreach($game->transactions->take(5) as $transaction)
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                        <small class="text-white fw-bold">{{ substr($transaction->user->name, 0, 1) }}</small>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">{{ $transaction->user->name }}</h6>
                        <small class="text-muted">{{ $transaction->created_at->format('d M Y H:i') }}</small>
                    </div>
                </div>
                @if(!$loop->last)<hr class="my-2">@endif
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Screenshot Modal -->
<div class="modal fade" id="screenshotModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Screenshot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalScreenshot" src="" alt="Screenshot" class="img-fluid">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const screenshotModal = document.getElementById('screenshotModal');
    const modalScreenshot = document.getElementById('modalScreenshot');
    
    screenshotModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const screenshot = button.getAttribute('data-screenshot');
        modalScreenshot.src = screenshot;
    });
});
</script>
@endpush