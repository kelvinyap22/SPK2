<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kriteria - SPK Beasiswa TOPSIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary mb-1">Sistem Pendukung Keputusan Beasiswa (Metode TOPSIS)</h2>
            <span class="text-muted">Edit Data Kriteria & Bobot</span>
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
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center">
                    <span>Edit Kriteria: {{ $kriteria->kode }} - {{ $kriteria->nama }}</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('kriteria.update', $kriteria->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Kode Kriteria</label>
                            <input type="text" name="kode" class="form-control" value="{{ old('kode', $kriteria->kode) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Nama Kriteria</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama', $kriteria->nama) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Sifat Kriteria</label>
                            <select name="sifat" class="form-select" required>
                                <option value="benefit" {{ old('sifat', $kriteria->sifat) == 'benefit' ? 'selected' : '' }}>Benefit</option>
                                <option value="cost" {{ old('sifat', $kriteria->sifat) == 'cost' ? 'selected' : '' }}>Cost</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-semibold">Bobot</label>
                            <input type="number" step="any" name="bobot" class="form-control" value="{{ old('bobot', $kriteria->bobot) }}" required>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <a href="{{ route('topsis.index') }}" class="btn btn-secondary btn-sm">Batal</a>
                            <button type="submit" class="btn btn-dark btn-sm fw-bold">Simpan Perubahan</button>
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
