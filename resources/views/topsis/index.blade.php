<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK Beasiswa - Metode TOPSIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4 fw-bold text-primary">Sistem Pendukung Keputusan Beasiswa (Metode TOPSIS)</h2>

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

    <div class="row">
        <!-- Kolom Kiri: Tabel Kriteria & Riwayat Log -->
        <div class="col-md-5 mb-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center">
                    <span>Data Kriteria & Bobot</span>
                    <button type="button" class="btn btn-sm btn-light py-0 px-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#tambahKriteriaModal">
                        <i class="bi bi-plus-lg"></i> Tambah
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Kriteria</th>
                                    <th>Sifat</th>
                                    <th>Bobot</th>
                                    <th class="text-center" style="width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kriterias as $k)
                                <tr>
                                    <td class="fw-bold">{{ $k->kode }}</td>
                                    <td>{{ $k->nama }}</td>
                                    <td>
                                        <span class="badge bg-{{ $k->sifat == 'benefit' ? 'success' : 'warning' }}">
                                            {{ $k->sifat }}
                                        </span>
                                    </td>
                                    <td>{{ $k->bobot }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-warning text-dark py-0 px-2" data-bs-toggle="modal" data-bs-target="#editKriteriaModal{{ $k->id }}" title="Edit Kriteria">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" data-bs-toggle="modal" data-bs-target="#deleteKriteriaModal{{ $k->id }}" title="Hapus Kriteria">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Belum ada data kriteria.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
                                <div class="d-flex gap-1 align-items-center">
                                    <a href="{{ route('topsis.show', $r->id) }}" class="btn btn-sm btn-outline-primary">Lihat Proses</a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteRiwayatModal{{ $r->id }}" title="Hapus Riwayat">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
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
                                    <th style="width: 140px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($alternatifs as $alt)
                                <tr>
                                    <td class="fw-bold">{{ $alt->kode_alternatif }}</td>
                                    <td class="text-start">{{ $alt->nama }}</td>
                                    @foreach($kriterias as $k)
                                        <td>{{ $alt->penilaians->where('kriteria_id', $k->id)->first()->nilai ?? '-' }}</td>
                                    @endforeach
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-warning text-dark py-0 px-2" data-bs-toggle="modal" data-bs-target="#editAlternatifModal{{ $alt->id }}" title="Edit Alternatif">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" data-bs-toggle="modal" data-bs-target="#deleteAlternatifModal{{ $alt->id }}" title="Hapus Alternatif">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ count($kriterias) + 3 }}" class="text-center text-muted py-3">Belum ada data alternatif siswa.</td>
                                </tr>
                                @endforelse
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

<!-- ================= MODAL TAMBAH KRITERIA ================= -->
<div class="modal fade" id="tambahKriteriaModal" tabindex="-1" aria-labelledby="tambahKriteriaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold" id="tambahKriteriaLabel">Tambah Kriteria Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('kriteria.store') }}" method="POST">
                @csrf
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Kode Kriteria</label>
                        <input type="text" name="kode" class="form-control" placeholder="Cth: C6" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Kriteria</label>
                        <input type="text" name="nama" class="form-control" placeholder="Cth: Ekstrakurikuler" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Sifat Kriteria</label>
                        <select name="sifat" class="form-select" required>
                            <option value="benefit" selected>Benefit</option>
                            <option value="cost">Cost</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Bobot</label>
                        <input type="number" step="any" name="bobot" class="form-control" placeholder="Cth: 0.1000" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark btn-sm fw-bold">Simpan Kriteria</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= MODAL EDIT & DELETE KRITERIA ================= -->
@foreach($kriterias as $k)
    <!-- Modal Edit Kriteria -->
    <div class="modal fade" id="editKriteriaModal{{ $k->id }}" tabindex="-1" aria-labelledby="editKriteriaLabel{{ $k->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content shadow">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold" id="editKriteriaLabel{{ $k->id }}">
                        Edit Kriteria: {{ $k->kode }} - {{ $k->nama }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('kriteria.update', $k->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body text-start">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Kode Kriteria</label>
                            <input type="text" name="kode" class="form-control" value="{{ old('kode', $k->kode) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Nama Kriteria</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama', $k->nama) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Sifat Kriteria</label>
                            <select name="sifat" class="form-select" required>
                                <option value="benefit" {{ old('sifat', $k->sifat) == 'benefit' ? 'selected' : '' }}>Benefit</option>
                                <option value="cost" {{ old('sifat', $k->sifat) == 'cost' ? 'selected' : '' }}>Cost</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Bobot</label>
                            <input type="number" step="any" name="bobot" class="form-control" value="{{ old('bobot', $k->bobot) }}" required>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-dark btn-sm fw-bold">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Delete Kriteria -->
    <div class="modal fade" id="deleteKriteriaModal{{ $k->id }}" tabindex="-1" aria-labelledby="deleteKriteriaLabel{{ $k->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold" id="deleteKriteriaLabel{{ $k->id }}">
                        Konfirmasi Hapus Kriteria
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    Apakah Anda yakin ingin menghapus kriteria <strong>{{ $k->kode }} - {{ $k->nama }}</strong>?
                    <div class="alert alert-warning mt-3 mb-0 small">
                        <i class="bi bi-exclamation-triangle-fill"></i> Perhatian: Nilai kriteria ini pada seluruh data alternatif juga akan terhapus.
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('kriteria.destroy', $k->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm fw-bold">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- ================= MODAL EDIT & DELETE ALTERNATIF ================= -->
@foreach($alternatifs as $alt)
    <!-- Modal Edit Alternatif -->
    <div class="modal fade" id="editAlternatifModal{{ $alt->id }}" tabindex="-1" aria-labelledby="editAlternatifLabel{{ $alt->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="editAlternatifLabel{{ $alt->id }}">
                        Edit Data Alternatif: {{ $alt->nama }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('alternatif.update', $alt->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body text-start">
                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Kode / NIS</label>
                                <input type="text" name="kode_alternatif" class="form-control" value="{{ old('kode_alternatif', $alt->kode_alternatif) }}" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label small fw-semibold">Nama Lengkap Siswa</label>
                                <input type="text" name="nama" class="form-control" value="{{ old('nama', $alt->nama) }}" required>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-bold text-secondary">Nilai Kriteria Siswa</label>
                        </div>
                        <div class="row g-2 mb-2">
                            @foreach($kriterias as $k)
                            @php
                                $val = $alt->penilaians->where('kriteria_id', $k->id)->first()->nilai ?? '';
                            @endphp
                            <div class="col-md-{{ count($kriterias) <= 5 ? '2' : '3' }} col-6 mb-2">
                                <label class="form-label small">
                                    <span class="badge bg-secondary">{{ $k->kode }}</span>
                                    <span class="d-block text-truncate" title="{{ $k->nama }}">{{ $k->nama }}</span>
                                </label>
                                <input type="number" step="any" name="nilai[{{ $k->id }}]" class="form-control" value="{{ old('nilai.' . $k->id, $val) }}" required>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm fw-bold">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Delete Alternatif -->
    <div class="modal fade" id="deleteAlternatifModal{{ $alt->id }}" tabindex="-1" aria-labelledby="deleteAlternatifLabel{{ $alt->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold" id="deleteAlternatifLabel{{ $alt->id }}">
                        Konfirmasi Hapus Alternatif
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    Apakah Anda yakin ingin menghapus data siswa <strong>{{ $alt->nama }}</strong> ({{ $alt->kode_alternatif }}) beserta seluruh nilai kriterianya?
                    <div class="alert alert-warning mt-3 mb-0 small">
                        <i class="bi bi-exclamation-triangle-fill"></i> Data yang dihapus tidak dapat dipulihkan.
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('alternatif.destroy', $alt->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm fw-bold">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- ================= MODAL DELETE RIWAYAT ================= -->
@foreach($riwayats as $r)
    <div class="modal fade" id="deleteRiwayatModal{{ $r->id }}" tabindex="-1" aria-labelledby="deleteRiwayatLabel{{ $r->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold" id="deleteRiwayatLabel{{ $r->id }}">
                        Konfirmasi Hapus Riwayat
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    Apakah Anda yakin ingin menghapus riwayat sesi perhitungan: <strong>{{ $r->nama_sesi }}</strong>?
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('topsis.destroy', $r->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm fw-bold">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
