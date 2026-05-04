@extends('layouts.app')

@section('title', 'Ubah Parameter Sensor')
@section('page-title', '✏️ Ubah Parameter Sensor')
@section('page-subtitle', 'Sensor: {{ $sensor->nama_sensor ?? "" }}')

@push('styles')
<style>
    .form-container {
        max-width: 800px;
    }
    .config-card {
        background: #fff;
        border-radius: 28px;
        padding: 40px;
        border: 2px solid rgba(0,0,0,0.06);
    }
    .config-section {
        margin-bottom: 40px;
    }
    .config-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f0f0f0;
    }

    .slider-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 28px;
    }
    .slider-item {
        background: #f8f9fa;
        padding: 24px;
        border-radius: 20px;
    }
    .slider-label {
        font-size: 15px;
        font-weight: 700;
        color: #555;
        margin-bottom: 16px;
        display: block;
    }
    .slider-hint {
        font-size: 13px;
        color: #aaa;
        margin-top: 8px;
    }
    .slider-input {
        width: 100%;
        height: 12px;
        background: #ddd;
        border-radius: 10px;
        outline: none;
        -webkit-appearance: none;
        cursor: pointer;
    }
    .slider-input::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 28px;
        height: 28px;
        background: var(--secondary);
        border: 4px solid #fff;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    .slider-input::-moz-range-thumb {
        width: 28px;
        height: 28px;
        background: var(--secondary);
        border: 4px solid #fff;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    .slider-display {
        display: inline-block;
        margin-top: 14px;
        font-size: 28px;
        font-weight: 800;
        color: var(--primary);
        background: #fff;
        padding: 6px 18px;
        border-radius: 12px;
    }

    .mode-selector {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .mode-option {
        position: relative;
    }
    .mode-option input {
        position: absolute;
        opacity: 0;
    }
    .mode-option label {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 28px 20px;
        background: #f8f9fa;
        border: 2px solid transparent;
        border-radius: 20px;
        cursor: pointer;
        transition: 0.2s;
        gap: 8px;
    }
    .mode-option input:checked + label {
        border-color: var(--secondary);
        background: #f0fdf4;
        box-shadow: 0 8px 24px rgba(45, 106, 79, 0.1);
    }
    .mode-option label:hover {
        border-color: var(--secondary);
    }
    .mode-icon { font-size: 32px; }
    .mode-name { font-size: 18px; font-weight: 700; color: var(--text); }
    .mode-desc { font-size: 13px; color: #888; }

    .action-btns {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 16px;
        margin-top: 40px;
    }
    .btn-save {
        background: var(--primary);
        color: #fff;
        padding: 20px;
        border: none;
        border-radius: 16px;
        font-size: 17px;
        font-weight: 800;
        cursor: pointer;
        font-family: inherit;
        transition: 0.2s;
    }
    .btn-save:hover {
        background: var(--secondary);
        transform: translateY(-2px);
    }
    .btn-back {
        background: #f0f0f0;
        color: #666;
        padding: 20px;
        border-radius: 16px;
        text-decoration: none;
        text-align: center;
        font-weight: 700;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
    }
    .btn-back:hover { background: #e0e0e0; }

    .error-msg {
        color: #dc2626;
        font-size: 13px;
        margin-top: 8px;
    }

    @media (max-width: 600px) {
        .slider-grid, .mode-selector, .action-btns { grid-template-columns: 1fr; }
        .config-card { padding: 24px; }
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <div class="config-card">
        <form action="{{ route('parameter.update', $parameter->id_parameter) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="config-section">
                <div class="config-title">💧 Ambang Kelembapan</div>
                <div class="slider-grid">
                    <div class="slider-item">
                        <label class="slider-label" for="min_k">Batas Minimum (Mulai Siram)</label>
                        <input type="range" name="min_kelembapan" id="min_k" class="slider-input"
                               min="0" max="100"
                               value="{{ old('min_kelembapan', $parameter->min_kelembapan) }}"
                               oninput="updateText('min_k', 'min_k_val', '%')">
                        <span class="slider-display" id="min_k_val">{{ number_format($parameter->min_kelembapan, 0) }}%</span>
                        <div class="slider-hint">Pompa menyala saat kelembapan di bawah ini</div>
                        @error('min_kelembapan')<div class="error-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="slider-item">
                        <label class="slider-label" for="max_k">Batas Maksimum (Berhenti)</label>
                        <input type="range" name="max_kelembapan" id="max_k" class="slider-input"
                               min="0" max="100"
                               value="{{ old('max_kelembapan', $parameter->max_kelembapan) }}"
                               oninput="updateText('max_k', 'max_k_val', '%')">
                        <span class="slider-display" id="max_k_val">{{ number_format($parameter->max_kelembapan, 0) }}%</span>
                        <div class="slider-hint">Pompa berhenti saat kelembapan di atas ini</div>
                        @error('max_kelembapan')<div class="error-msg">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="config-section">
                <div class="config-title">🧪 Rentang pH Tanah</div>
                <div class="slider-grid">
                    <div class="slider-item">
                        <label class="slider-label" for="min_p">pH Minimum Aman</label>
                        <input type="range" name="min_ph" id="min_p" class="slider-input"
                               min="0" max="14" step="0.1"
                               value="{{ old('min_ph', $parameter->min_ph) }}"
                               oninput="updateText('min_p', 'min_p_val', '')">
                        <span class="slider-display" id="min_p_val">{{ number_format($parameter->min_ph, 1) }}</span>
                        @error('min_ph')<div class="error-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="slider-item">
                        <label class="slider-label" for="max_p">pH Maksimum Aman</label>
                        <input type="range" name="max_ph" id="max_p" class="slider-input"
                               min="0" max="14" step="0.1"
                               value="{{ old('max_ph', $parameter->max_ph) }}"
                               oninput="updateText('max_p', 'max_p_val', '')">
                        <span class="slider-display" id="max_p_val">{{ number_format($parameter->max_ph, 1) }}</span>
                        @error('max_ph')<div class="error-msg">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="config-section">
                <div class="config-title">⚙️ Pilih Mode</div>
                <div class="mode-selector">
                    <div class="mode-option">
                        <input type="radio" name="mode_auto" id="auto" value="1"
                               {{ old('mode_auto', $parameter->mode_auto) ? 'checked' : '' }}>
                        <label for="auto">
                            <span class="mode-icon">⚡</span>
                            <span class="mode-name">Otomatis</span>
                            <span class="mode-desc">Sistem menyiram sendiri</span>
                        </label>
                    </div>
                    <div class="mode-option">
                        <input type="radio" name="mode_auto" id="manual" value="0"
                               {{ !old('mode_auto', $parameter->mode_auto) ? 'checked' : '' }}>
                        <label for="manual">
                            <span class="mode-icon">🖐</span>
                            <span class="mode-name">Manual</span>
                            <span class="mode-desc">Anda kontrol sendiri</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="action-btns">
                <button type="submit" class="btn-save">💾 Simpan Pengaturan</button>
                <a href="{{ route('parameter.index') }}" class="btn-back">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateText(inputId, displayId, suffix) {
    document.getElementById(displayId).innerText = document.getElementById(inputId).value + suffix;
}
</script>
@endpush
