@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('style')
    <!-- CSS Libraries -->
    <style>
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        .stat-card {
            transition: all 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
    </style>
@endpush

@section('content')
    <div class="section-body">
        <!-- Statistik Cards -->
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card card-statistic-1 stat-card">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Surat Masuk</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalSuratMasuk }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card card-statistic-1 stat-card">
                    <div class="card-icon bg-success">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Surat Keluar</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalSuratKeluar }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Surat Bulanan -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Statistik Surat Masuk & Keluar (6 Bulan Terakhir)</h4>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="monthlySuratChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Chart & Distribusi -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Jenis Surat Keluar</h4>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="jenisSuratKeluarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Status Disposisi Surat Masuk</h4>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="statusDisposisiChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabel Latest Surat -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Surat Masuk Terbaru</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No. Surat</th>
                                        <th>Perihal</th>
                                        <th>Tanggal Terima</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($latestSuratMasuk as $surat)
                                    <tr>
                                        <td>{{ $surat->no_surat }}</td>
                                        <td>{{ Str::limit($surat->perihal, 30) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($surat->tgl_terima)->format('d/m/Y') }}</td>
                                        <td>
                                            @if ($surat->status_disposisi === 'sudah_disposisi')
                                                <span class="badge badge-success">Sudah Disposisi</span>
                                            @elseif ($surat->status_disposisi === 'tidak_perlu_disposisi')
                                                <span class="badge badge-secondary">Tidak Perlu Disposisi</span>
                                            @else
                                                <span class="badge badge-warning">Belum Ditentukan</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data surat masuk</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Surat Keluar Terbaru</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No. Surat</th>
                                        <th>Nomor Agenda</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($latestSuratKeluar as $surat)
                                    <tr>
                                        <td>{{ $surat->no_surat_keluar }}</td>
                                        <td>{{ $surat->nomor_agenda }}</td>
                                        <td>
                                            @if ($surat->status == 'draft')
                                                <span class="badge badge-warning">Draft</span>
                                            @elseif ($surat->status == 'terkirim')
                                                <span class="badge badge-success">Terkirim</span>
                                            @else
                                                <span class="badge badge-info">{{ ucfirst($surat->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada data surat keluar</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Tambahkan Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        // Data dari controller
        const chartSuratMasuk = @json($chartSuratMasuk);
        const chartSuratKeluar = @json($chartSuratKeluar);
        const jenisSuratKeluar = @json($jenisSuratKeluar);
        const statusDisposisi = @json($statusDisposisi);

        // Line Chart untuk Surat Masuk & Keluar per Bulan
        const monthlySuratCtx = document.getElementById('monthlySuratChart').getContext('2d');
        const monthlySuratChart = new Chart(monthlySuratCtx, {
            type: 'line',
            data: {
                labels: chartSuratMasuk.labels,
                datasets: [
                    {
                        label: 'Surat Masuk',
                        data: chartSuratMasuk.data,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        pointRadius: 4
                    },
                    {
                        label: 'Surat Keluar',
                        data: chartSuratKeluar.data,
                        backgroundColor: 'rgba(255, 159, 64, 0.2)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                }
            }
        });

        // Pie Chart untuk Jenis Surat Keluar
        const jenisSuratKeluarCtx = document.getElementById('jenisSuratKeluarChart').getContext('2d');
        const jenisSuratKeluarChart = new Chart(jenisSuratKeluarCtx, {
            type: 'pie',
            data: {
                labels: jenisSuratKeluar.labels,
                datasets: [{
                    data: jenisSuratKeluar.data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((acc, data) => acc + data, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
        
        // Doughnut Chart untuk Status Disposisi Surat Masuk
        const statusDisposisiCtx = document.getElementById('statusDisposisiChart').getContext('2d');
        const statusDisposisiChart = new Chart(statusDisposisiCtx, {
            type: 'doughnut',
            data: {
                labels: statusDisposisi.labels,
                datasets: [{
                    data: statusDisposisi.data,
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.7)',  // Sudah Disposisi
                        'rgba(255, 206, 86, 0.7)',  // Belum Ditentukan
                        'rgba(153, 102, 255, 0.7)'  // Tidak Perlu Disposisi
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((acc, data) => acc + data, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush