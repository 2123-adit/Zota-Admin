@extends('layouts.app')

@section('title', 'Edit Game - ZOTA Admin')
@section('page-title', 'Edit Game')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2 text-warning"></i>Edit Game: {{ $game->name }}
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('games.update', $game->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Game</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $game->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $game->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="4" placeholder="Masukkan deskripsi game...">{{ old('description', $game->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Harga (Rp)</label>
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
                                       value="{{ old('price', $game->price) }}" min="0" step="1000" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Gambar Cover</label>
                                <input type="file" name="cover_image" class="form-control @error('cover_image') is-invalid @enderror" 
                                       accept="image/*">
                                @if($game->cover_image)
                                    <small class="text-muted">Saat ini: {{ basename($game->cover_image) }}</small>
                                @endif
                                @error('cover_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    @if($game->cover_image)
                    <div class="mb-3">
                        <label class="form-label">Gambar Cover Saat Ini</label>
                        <div>
                            <img src="{{ $game->cover_image_url }}" alt="{{ $game->name }}" 
                                 class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Screenshot</label>
                        <input type="file" name="screenshots[]" class="form-control @error('screenshots.*') is-invalid @enderror" 
                               accept="image/*" multiple>
                        <small class="text-muted">Biarkan kosong untuk mempertahankan screenshot saat ini, atau pilih yang baru untuk mengganti semua</small>
                        @error('screenshots.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($game->screenshots)
                    <div class="mb-3">
                        <label class="form-label">Screenshot Saat Ini</label>
                        <div class="row">
                            @foreach($game->screenshots_url as $screenshot)
                            <div class="col-md-3 mb-2">
                                <img src="{{ $screenshot }}" alt="Screenshot" 
                                     class="img-thumbnail w-100">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input type="checkbox" name="is_featured" class="form-check-input" id="is_featured" 
                                       {{ old('is_featured', $game->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    Game Unggulan
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input type="checkbox" name="is_active" class="form-check-input" id="is_active" 
                                       {{ old('is_active', $game->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Aktif
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-modern">
                            <i class="fas fa-save me-2"></i>Perbarui Game
                        </button>
                        <a href="{{ route('games.index') }}" class="btn btn-secondary btn-modern">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Game
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection