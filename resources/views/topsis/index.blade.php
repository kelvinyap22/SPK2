<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK Beasiswa - Metode TOPSIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4 fw-bold text-primary">Sistem Pendukung Keputusan Beasiswa (Metode TOPSIS & AHP)</h2>

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

    <div class="row">
        <!-- Kolom Kiri: Tabel Kriteria & Riwayat Log -->
        <div class="col-md-5 mb-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white fw-bold">Data Kriteria & Bobot AHP</div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama Kriteria</th>
                                <th>Sifat</th>
                                <th>Bobot</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kriterias as $k)
                            <tr>
                                <td>{{ $k->kode }}</td>
                                <td>{{ $k->nama }}</td>
                                <td>
                                    <span class="badge bg-{{ $k->sifat == 'benefit' ? 'success' : 'warning' }}">
                                        {{ $k->sifat }}
                                    </span>
                                </td>
                                <td>{{ $k->bobot }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Riwayat Sesi Perhitungan (Menjawab Masalah #1: Proses Dicatat) -->
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white fw-bold">Log Riwayat Perhitungan Sebelumnya</div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($riwayats as $r)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $r->nama_sesi }}</strong><br>
                                    <small class="text-muted">{{ $r->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <a href="{{ route('topsis.show', $r->id) }}" class="btn btn-sm btn-outline-primary">Lihat Proses</a>
                            </li>
                        @empty
                            <li class="list-group-item text-muted text-center py-3">Belum ada riwayat perhitungan tersimpan.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Data Alternatif & Aksi Hitung -->
        <div class="col-md-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white fw-bold d-flex justify-content-between align-items-center">
                    <span>Matriks Keputusan Alternatif (Siswa)</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Siswa</th>
                                    @foreach($kriterias as $k)
                                        <th>{{ $k->kode }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alternatifs as $alt)
                                <tr>
                                    <td>{{ $alt->kode_alternatif }}</td>
                                    <td class="text-start">{{ $alt->nama }}</td>
                                    @foreach($kriterias as $k)
                                        <td>{{ $alt->penilaians->where('kriteria_id', $k->id)->first()->nilai ?? '-' }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Form Eksekusi Hitung TOPSIS -->
                    <form action="{{ route('topsis.hitung') }}" method="POST" class="mt-4 p-3 bg-light border rounded">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Sesi Perhitungan / Periode</label>
                            <input type="text" name="nama_sesi" class="form-control" placeholder="Cth: Seleksi Beasiswa Semester Genap 2026" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100 fw-bold">Hitung TOPSIS & Simpan Proses</button>
                    </form>
                </div>
            </div>

            <!-- Form Tambah Alternatif Baru -->
            <div class="card shadow-sm">
                <div class="card-header fw-bold">Tambah Data Alternatif (Siswa)</div>
                <div class="card-body">
                    <form action="{{ route('alternatif.store') }}" method="POST">
                        @csrf
                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <label class="form-label small">Kode / NIS</label>
                                <input type="text" name="kode_alternatif" class="form-control" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label small">Nama Lengkap Siswa</label>
                                <input type="text" name="nama" class="form-control" required>
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            @foreach($kriterias as $k)
                            <div class="col">
                                <label class="form-label small">{{ $k->kode }}</label>
                                <input type="number" step="any" name="nilai[{{ $k->id }}]" class="form-control" required>
                            </div>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-dark btn-sm">Simpan Alternatif Baru</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>