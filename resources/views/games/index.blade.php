@extends('layouts.app')

@section('title', 'Games Management - ZOTA Admin')
@section('page-title', 'Games Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-gamepad me-2 text-primary"></i>All Games
                        </h5>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('games.create') }}" class="btn btn-success btn-modern">
                            <i class="fas fa-plus me-2"></i>Add New Game
                        </a>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col">
                        <form method="GET" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control" placeholder="Search games..." value="{{ request('search') }}">
                            <select name="category_id" class="form-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-modern">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Game</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Sales</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($games as $game)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            @if($game->cover_image)
                                                <img src="{{ $game->cover_image_url }}" alt="{{ $game->name }}" 
                                                     class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-gamepad text-white"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $game->name }}</h6>
                                            @if($game->is_featured)
                                                <span class="badge bg-warning text-dark">Featured</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $game->category->name }}</td>
                                <td class="fw-bold text-success">Rp {{ number_format($game->price, 0, ',', '.') }}</td>
                                <td>{{ $game->transactions_count }}</td>
                                <td>
                                    @if($game->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('games.show', $game->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('games.edit', $game->id) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('games.destroy', $game->id) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $games->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection