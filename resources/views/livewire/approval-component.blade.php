<div>
    <h2>Persetujuan untuk {{ $approvableType }}</h2>

    @foreach ($approvals as $approval)
        <div>
            <h4>{{ $approval->role == 'ppk' ? 'PPK' : ($approval->role == 'pptk' ? 'PPTK' : 'Penanggung Jawab Final') }}: 
                {{ $approval->is_approved ? 'Disetujui' : 'Belum Disetujui' }}
            </h4>

            @if (!$approval->is_approved)
                <button wire:click="approve({{ $approval->id }})" class="btn btn-primary">
                    Setujui
                </button>
            @endif
        </div>
    @endforeach

    <!-- Flash message -->
    @if (session()->has('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @elseif (session()->has('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif
</div>
