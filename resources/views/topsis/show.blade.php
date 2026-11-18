<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Proses TOPSIS - {{ $perhitungan->nama_sesi }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
@php
    $data = $perhitungan->detail_proses;
    $kriterias = $data['kriterias'];
@endphp

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0">Detail Proses: {{ $perhitungan->nama_sesi }}</h3>
            <small class="text-muted">Waktu Pencatatan Sesi: {{ $perhitungan->created_at->format('d M Y, H:i:s') }}</small>
        </div>
        <a href="{{ route('topsis.index') }}" class="btn btn-secondary">Kembali ke Beranda</a>
    </div>

    <!-- Step 1: Perangkingan Akhir -->
    <div class="card shadow-sm mb-4 border-success">
        <div class="card-header bg-success text-white fw-bold">1. Hasil Akhir & Perangkingan Alternatif (Nilai Preferensi V)</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Ranking</th>
                        <th>Kode</th>
                        <th>Nama Siswa</th>
                        <th>Jarak Positif (D+)</th>
                        <th>Jarak Negatif (D-)</th>
                        <th>Nilai Preferensi (V)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['ranking'] as $index => $row)
                    <tr class="{{ $index == 0 ? 'table-success fw-bold' : '' }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row['kode'] }}</td>
                        <td class="text-start">{{ $row['nama'] }}</td>
                        <td>{{ $row['d_plus'] }}</td>
                        <td>{{ $row['d_minus'] }}</td>
                        <td>{{ $row['preferensi'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Step 2: Solusi Ideal Positif & Negatif -->
    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold bg-dark text-white">2. Matriks Solusi Ideal Positif (A+) & Negatif (A-)</div>
        <div class="card-body table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Solusi Ideal</th>
                        @foreach($kriterias as $k)
                            <th>{{ $k['kode'] }} ({{ $k['sifat'] }})</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-bold text-success">A+ (Ideal Positif)</td>
                        @foreach($kriterias as $k)
                            <td>{{ round($data['A_plus'][$k['id']], 4) }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="fw-bold text-danger">A- (Ideal Negatif)</td>
                        @foreach($kriterias as $k)
                            <td>{{ round($data['A_minus'][$k['id']], 4) }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <!-- Step 3: Matriks Ternormalisasi (R) -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">3. Matriks Keputusan Ternormalisasi (R)</div>
                <div class="card-body table-responsive">
                    <table class="table table-sm table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Alternatif</th>
                                @foreach($kriterias as $k) <th>{{ $k['kode'] }}</th> @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['ranking'] as $row)
                            <tr>
                                <td class="text-start">{{ $row['nama'] }}</td>
                                @foreach($kriterias as $k)
                                    <td>{{ round($data['R'][$row['alternatif_id']][$k['id']], 4) }}</td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Step 4: Matriks Ternormalisasi Terbobot (Y) -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">4. Matriks Ternormalisasi Terbobot (Y)</div>
                <div class="card-body table-responsive">
                    <table class="table table-sm table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Alternatif</th>
                                @foreach($kriterias as $k) <th>{{ $k['kode'] }}</th> @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['ranking'] as $row)
                            <tr>
                                <td class="text-start">{{ $row['nama'] }}</td>
                                @foreach($kriterias as $k)
                                    <td>{{ round($data['Y'][$row['alternatif_id']][$k['id']], 4) }}</td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>