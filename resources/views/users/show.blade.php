<!-- C:\laragon\www\zota-admin\resources\views\users\show.blade.php: -->
@extends('layouts.app')

@section('title', 'Detail Pengguna - ZOTA Admin')
@section('page-title', 'Detail Pengguna')

@push('styles')
<style>
    .balance-form-card {
        background: rgba(120, 80, 255, 0.1);
        border: 2px solid rgba(120, 80, 255, 0.3);
        border-radius: 15px;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }
    
    .balance-form-card.show {
        background: rgba(120, 80, 255, 0.15);
        border-color: rgba(120, 80, 255, 0.5);
        box-shadow: 0 10px 30px rgba(120, 80, 255, 0.2);
    }
    
    .collapse-toggle {
        transition: all 0.3s ease;
    }
    
    .collapse-toggle[aria-expanded="true"] i {
        transform: rotate(180deg);
    }
    
    .form-control, .form-select {
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #333;
    }
    
    .form-control:focus, .form-select:focus {
        background: rgba(255, 255, 255, 1);
        border-color: rgba(120, 80, 255, 0.5);
        color: #333;
        box-shadow: 0 0 0 0.2rem rgba(120, 80, 255, 0.25);
    }
    
    /* Styling khusus untuk select options */
    .form-select option {
        background: #fff;
        color: #333;
        padding: 8px;
    }
    
    .form-select option:hover, .form-select option:focus {
        background: rgba(120, 80, 255, 0.1);
        color: #333;
    }
    
    .form-control::placeholder {
        color: rgba(100, 100, 100, 0.7);
    }
    
    .form-label {
        color: rgba(255, 255, 255, 0.9);
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2 text-primary"></i>Informasi Pengguna
                </h5>
            </div>
            <div class="card-body text-center">
                <div class="avatar-lg bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                    <h3 class="text-white mb-0">{{ substr($user->name, 0, 1) }}</h3>
                </div>
                <h4>{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->email }}</p>
                
                <div class="row text-center mt-4">
                    <div class="col-6">
                        <h5 class="text-success mb-0">Rp {{ number_format($user->balance, 0, ',', '.') }}</h5>
                        <small class="text-muted">Saldo Saat Ini</small>
                    </div>
                    <div class="col-6">
                        <h5 class="text-info mb-0">{{ $user->userGames->count() }}</h5>
                        <small class="text-muted">Game Dimiliki</small>
                    </div>
                </div>

                <div class="mt-4">
                    @if($user->is_active)
                        <span class="badge bg-success px-3 py-2">Akun Aktif</span>
                    @else
                        <span class="badge bg-danger px-3 py-2">Akun Diblokir</span>
                    @endif
                </div>

                <!-- Tombol Toggle Form Penyesuaian Saldo -->
                <div class="mt-4">
                    <button class="btn btn-primary btn-modern collapse-toggle" 
                            type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#balanceAdjustForm" 
                            aria-expanded="false" 
                            aria-controls="balanceAdjustForm">
                        <i class="fas fa-wallet me-2"></i>Sesuaikan Saldo
                        <i class="fas fa-chevron-down ms-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Form Penyesuaian Saldo (Collapsible) -->
        <div class="collapse mt-3" id="balanceAdjustForm">
            <div class="card balance-form-card">
                <div class="card-header" style="background: rgba(120, 80, 255, 0.2); border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                    <h6 class="mb-0 text-white">
                        <i class="fas fa-cog me-2"></i>Penyesuaian Saldo
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('users.adjust-balance', $user->id) }}" id="adjustBalanceForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="currentBalance" class="form-label">Saldo Saat Ini</label>
                            <input type="text" 
                                   id="currentBalance" 
                                   class="form-control" 
                                   value="Rp {{ number_format($user->balance, 0, ',', '.') }}" 
                                   readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="adjustType" class="form-label">Pilih Aksi</label>
                            <select name="type" id="adjustType" class="form-select" required>
                                <option value="">Pilih Aksi</option>
                                <option value="add">
                                    <i class="fas fa-plus"></i> Tambah Saldo
                                </option>
                                <option value="subtract">
                                    <i class="fas fa-minus"></i> Kurangi Saldo
                                </option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="adjustAmount" class="form-label">Jumlah (Rp)</label>
                            <input type="number" 
                                   name="amount" 
                                   id="adjustAmount" 
                                   class="form-control" 
                                   min="0" 
                                   step="1000" 
                                   placeholder="Masukkan jumlah..."
                                   required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="adjustNotes" class="form-label">Catatan (Opsional)</label>
                            <textarea name="notes" 
                                      id="adjustNotes" 
                                      class="form-control" 
                                      rows="3" 
                                      placeholder="Masukkan catatan atau alasan penyesuaian..."></textarea>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success" id="submitAdjustment">
                                <i class="fas fa-check me-2"></i>Sesuaikan Saldo
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                <i class="fas fa-undo me-2"></i>Reset Form
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Transaksi Terbaru -->
        <div class="card">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2 text-primary"></i>Transaksi Terbaru
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Jenis</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->transactions->take(10) as $transaction)
                            <tr>
                                <td>
                                    @if($transaction->type === 'purchase')
                                        <span class="badge bg-success">Pembelian</span>
                                        <br><small class="text-muted">{{ $transaction->game->name ?? 'N/A' }}</small>
                                    @else
                                        <span class="badge bg-info">Top-up</span>
                                    @endif
                                </td>
                                <td class="fw-bold">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                <td>
                                    @if($transaction->status === 'completed')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($transaction->status === 'pending')
                                        <span class="badge bg-warning">Tertunda</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($transaction->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('adjustBalanceForm');
    const submitBtn = document.getElementById('submitAdjustment');
    const balanceForm = document.getElementById('balanceAdjustForm');
    
    // Handle form submission
    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
            
            // Re-enable after 5 seconds as fallback
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check me-2"></i>Sesuaikan Saldo';
            }, 5000);
        });
    }
    
    // Handle collapse events
    if (balanceForm) {
        balanceForm.addEventListener('shown.bs.collapse', function () {
            // Focus on select when form is shown
            const firstSelect = document.getElementById('adjustType');
            if (firstSelect) {
                firstSelect.focus();
            }
            
            // Add show class for styling
            const card = balanceForm.querySelector('.balance-form-card');
            if (card) {
                card.classList.add('show');
            }
        });
        
        balanceForm.addEventListener('hidden.bs.collapse', function () {
            // Remove show class
            const card = balanceForm.querySelector('.balance-form-card');
            if (card) {
                card.classList.remove('show');
            }
        });
    }
    
    // Validation feedback
    const amountInput = document.getElementById('adjustAmount');
    const typeSelect = document.getElementById('adjustType');
    
    if (amountInput && typeSelect) {
        amountInput.addEventListener('input', function() {
            const amount = parseFloat(this.value);
            const currentBalance = {{ $user->balance }};
            
            if (typeSelect.value === 'subtract' && amount > currentBalance) {
                this.setCustomValidity('Jumlah tidak boleh melebihi saldo saat ini');
                this.classList.add('is-invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
            }
        });
        
        typeSelect.addEventListener('change', function() {
            // Trigger amount validation when type changes
            if (amountInput.value) {
                amountInput.dispatchEvent(new Event('input'));
            }
        });
    }
});

// Reset form function
function resetForm() {
    const form = document.getElementById('adjustBalanceForm');
    if (form) {
        form.reset();
        
        // Remove validation classes
        const inputs = form.querySelectorAll('.is-invalid');
        inputs.forEach(input => input.classList.remove('is-invalid'));
        
        // Reset submit button
        const submitBtn = document.getElementById('submitAdjustment');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-check me-2"></i>Sesuaikan Saldo';
        }
    }
}
</script>
@endpush