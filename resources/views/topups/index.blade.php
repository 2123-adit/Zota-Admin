@extends('layouts.app')

@section('title', 'Manajemen Top-up - ZOTA Admin')
@section('page-title', 'Manajemen Top-up')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white border-0">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-credit-card me-2 text-primary"></i>Permintaan Top-up
                        </h5>
                    </div>
                    <div class="col-auto">
                        <form method="GET" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control" placeholder="Cari pengguna..." value="{{ request('search') }}">
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Tertunda</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
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
                                <th>ID Permintaan</th>
                                <th>Pengguna</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Diminta</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topups as $topup)
                            <tr>
                                <td class="fw-bold">{{ $topup->request_id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <small class="text-white fw-bold">{{ substr($topup->user->name, 0, 1) }}</small>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $topup->user->name }}</h6>
                                            <small class="text-muted">{{ $topup->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-bold text-success">Rp {{ number_format($topup->amount, 0, ',', '.') }}</td>
                                <td>
                                    @if($topup->status === 'pending')
                                        <span class="badge bg-warning">Tertunda</span>
                                    @elseif($topup->status === 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $topup->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('topups.show', $topup->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($topup->status === 'pending')
                                            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $topup->id }}">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $topup->id }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Setujui -->
                            <div class="modal fade" id="approveModal{{ $topup->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('topups.approve', $topup->id) }}">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Setujui Permintaan Top-up</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menyetujui permintaan top-up sebesar <strong>Rp {{ number_format($topup->amount, 0, ',', '.') }}</strong>?</p>
                                                <div class="mb-3">
                                                    <label class="form-label">Catatan (Opsional)</label>
                                                    <textarea name="notes" class="form-control" rows="3" placeholder="Masukkan catatan..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success">Setujui</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Tolak -->
                            <div class="modal fade" id="rejectModal{{ $topup->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('topups.reject', $topup->id) }}">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Tolak Permintaan Top-up</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menolak permintaan top-up sebesar <strong>Rp {{ number_format($topup->amount, 0, ',', '.') }}</strong>?</p>
                                                <div class="mb-3">
                                                    <label class="form-label">Alasan Penolakan</label>
                                                    <textarea name="notes" class="form-control" rows="3" required placeholder="Harap berikan alasan penolakan"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Tolak</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $topups->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection