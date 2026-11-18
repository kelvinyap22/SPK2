<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Alternatif - SPK Beasiswa TOPSIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary mb-1">Sistem Pendukung Keputusan Beasiswa (Metode TOPSIS)</h2>
            <span class="text-muted">Edit Data Alternatif Siswa</span>
        </div>
        <a href="{{ route('topsis.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-bold d-flex justify-content-between align-items-center">
                    <span>Edit Data Alternatif: {{ $alternatif->nama }} ({{ $alternatif->kode_alternatif }})</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('alternatif.update', $alternatif->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Kode / NIS</label>
                                <input type="text" name="kode_alternatif" class="form-control" value="{{ old('kode_alternatif', $alternatif->kode_alternatif) }}" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label small fw-semibold">Nama Lengkap Siswa</label>
                                <input type="text" name="nama" class="form-control" value="{{ old('nama', $alternatif->nama) }}" required>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-bold text-secondary">Nilai Kriteria Siswa</label>
                        </div>
                        <div class="row g-2 mb-4">
                            @foreach($kriterias as $k)
                            @php
                                $val = $alternatif->penilaians->where('kriteria_id', $k->id)->first()->nilai ?? '';
                            @endphp
                            <div class="col-md-{{ count($kriterias) <= 5 ? '2' : '3' }} col-6 mb-2">
                                <label class="form-label small">
                                    <span class="badge bg-{{ $k->sifat == 'benefit' ? 'success' : 'warning' }}">{{ $k->kode }}</span>
                                    <span class="d-block text-truncate" title="{{ $k->nama }}">{{ $k->nama }}</span>
                                </label>
                                <input type="number" step="any" name="nilai[{{ $k->id }}]" class="form-control" value="{{ old('nilai.' . $k->id, $val) }}" required>
                            </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <a href="{{ route('topsis.index') }}" class="btn btn-secondary btn-sm">Batal</a>
                            <button type="submit" class="btn btn-primary btn-sm fw-bold">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
