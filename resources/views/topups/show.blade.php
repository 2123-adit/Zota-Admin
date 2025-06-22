@extends('layouts.app')

@section('title', 'Detail Top-up - ZOTA Admin')
@section('page-title', 'Detail Top-up')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-credit-card me-2 text-primary"></i>Detail Permintaan Top-up
                    </h5>
                    <div>
                        @if($topup->status === 'pending')
                            <span class="badge bg-warning px-3 py-2">Tertunda</span>
                        @elseif($topup->status === 'approved')
                            <span class="badge bg-success px-3 py-2">Disetujui</span>
                        @else
                            <span class="badge bg-danger px-3 py-2">Ditolak</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6>Informasi Permintaan</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted">ID Permintaan:</td>
                                    <td class="fw-bold">{{ $topup->request_id }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Jumlah:</td>
                                    <td class="fw-bold text-success">Rp {{ number_format($topup->amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status:</td>
                                    <td>
                                        @if($topup->status === 'pending')
                                            <span class="badge bg-warning">Tertunda</span>
                                        @elseif($topup->status === 'approved')
                                            <span class="badge bg-success">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Diminta Pada:</td>
                                    <td>{{ $topup->created_at->format('d M Y H:i:s') }}</td>
                                </tr>
                                @if($topup->processed_at)
                                <tr>
                                    <td class="text-muted">Diproses Pada:</td>
                                    <td>{{ $topup->processed_at->format('d M Y H:i:s') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6>Informasi Pengguna</h6>
                            <div class="d-flex align-items-center">
                                <div class="avatar-lg bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                    <h4 class="text-white mb-0">{{ substr($topup->user->name, 0, 1) }}</h4>
                                </div>
                                <div>
                                    <h5 class="mb-1">{{ $topup->user->name }}</h5>
                                    <p class="text-muted mb-1">{{ $topup->user->email }}</p>
                                    <small class="text-muted">Saldo Saat Ini: <span class="fw-bold text-success">Rp {{ number_format($topup->user->balance, 0, ',', '.') }}</span></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($topup->notes)
                <div class="mb-4">
                    <h6>Catatan</h6>
                    <div class="bg-light p-3 rounded">
                        {{ $topup->notes }}
                    </div>
                </div>
                @endif

                <div class="d-flex gap-2">
                    @if($topup->status === 'pending')
                        <button class="btn btn-success btn-modern" data-bs-toggle="modal" data-bs-target="#approveModal">
                            <i class="fas fa-check me-2"></i>Setujui
                        </button>
                        <button class="btn btn-danger btn-modern" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="fas fa-times me-2"></i>Tolak
                        </button>
                    @endif
                    <a href="{{ route('topups.index') }}" class="btn btn-secondary btn-modern">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Top-up
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Setujui -->
<div class="modal fade" id="approveModal" tabindex="-1">
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
<div class="modal fade" id="rejectModal" tabindex="-1">
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
@endsection