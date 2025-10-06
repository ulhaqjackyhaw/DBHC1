@extends('layouts.app')

{{-- Mengisi judul tab browser --}}
@section('title', 'Dashboard Utama')

{{-- Mengisi judul di header --}}
@section('header-title', 'Dashboard Kepegawaian Regional 1 PT Angkasa Pura Indonesia')

{{-- Script & Style di head --}}
@push('head-scripts')
    {{-- Modern Font & Icons --}}
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    {{-- Script Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- Opsional: plugin datalabels untuk menampilkan nilai & persen di chart --}}
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    {{-- CSS Modern --}}
    <style>
        /* ===== GLOBAL & TYPOGRAPHY ===== */
        :root {
            --primary-color: #4f46e5;
            /* Indigo */
            --body-bg: #f8fafc;
            /* Slate 50 */
            --card-bg: #ffffff;
            --text-color-dark: #1e293b;
            /* Slate 800 */
            --text-color-light: #64748b;
            /* Slate 500 */
            --border-color: #e2e8f0;
            /* Slate 200 */
        }

        body {
            background-color: var(--body-bg);
            font-family: 'Inter', sans-serif;
            color: var(--text-color-dark);
        }

        .fw-semibold {
            font-weight: 600 !important;
        }

        /* ===== REFINED CARDS ===== */
        .card {
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05);
            background-color: var(--card-bg);
        }

        .card-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-color-dark);
            margin-bottom: 1rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* ===== MODERN KPI WIDGETS ===== */
        .kpi {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.25rem;
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05);
            transition: all .2s ease-in-out;
        }

        .kpi:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.07), 0 4px 6px -4px rgb(0 0 0 / 0.07);
        }

        .kpi .icon {
            flex-shrink: 0;
            display: grid;
            place-items: center;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            font-size: 1.25rem;
        }

        .kpi .value {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
            color: var(--text-color-dark);
        }

        .kpi .label {
            font-size: .875rem;
            color: var(--text-color-light);
        }

        .kpi .icon-total {
            background-color: #e0e7ff;
            color: #4338ca;
        }

        .kpi .icon-female {
            background-color: #fce7f3;
            color: #db2777;
        }

        .kpi .icon-male {
            background-color: #dbeafe;
            color: #2563eb;
        }

        .kpi .icon-age {
            background-color: #dcfce7;
            color: #16a34a;
        }

        .kpi .icon-mk {
            background-color: #ffedd5;
            color: #f97316;
        }

        /* ===== IMPROVED TABLES ===== */
        .table {
            border-color: var(--border-color);
        }

        .table thead th {
            background: #f1f5f9;
            /* Slate 100 */
            border-bottom: 2px solid var(--border-color);
            color: var(--text-color-light);
            font-weight: 600;
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
        }

        .table-fixed {
            table-layout: fixed;
        }

        .table-fixed td,
        .table-fixed th {
            word-wrap: break-word;
            vertical-align: middle;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .table-fixed tbody td {
            border-left: 1px solid var(--border-color);
        }

        .table-fixed tbody td:first-child {
            border-left: 0;
        }

        .h-rows {
            max-height: 420px;
            overflow: auto;
        }

        .cell-name {
            font-weight: 600;
            line-height: 1.2;
            color: var(--text-color-dark);
        }

        .cell-role {
            font-size: .85rem;
            color: var(--text-color-light);
        }

        /* ===== CSS BARU & PENYESUAIAN UNTUK JABATAN LOWONG ===== */
        .total-lowongan-card {
            /* Kartu total keseluruhan */
            background-color: var(--primary-color);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: .75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .total-lowongan-card .value {
            font-size: 2.25rem;
            font-weight: 800;
        }

        .lowongan-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .lowongan-group {
            border: 1px solid var(--border-color);
            border-radius: .75rem;
            overflow: hidden;
            background-color: #f8fafc;
        }

        .lowongan-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            background-color: #f1f5f9;
            font-weight: 600;
            border-bottom: 1px solid var(--border-color);
        }

        .lowongan-header i {
            color: var(--primary-color);
        }

        .lowongan-body {
            padding: .5rem;
        }

        .lowongan-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.6rem 0.5rem;
            border-radius: .5rem;
        }

        .lowongan-item:hover {
            background-color: #eef2ff;
        }

        .lowongan-item.clickable-item:hover {
            background-color: #e0e7ff;
            transform: translateX(2px);
            transition: all 0.2s ease;
        }

        .lowongan-item.clickable-item:active {
            transform: translateX(0px);
        }

        .lowongan-level {
            font-size: .9rem;
            color: var(--text-color-dark);
        }

        .lowongan-badge {
            background-color: var(--primary-color);
            color: white;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.25rem 0.6rem;
            border-radius: 9999px;
            min-width: 28px;
            text-align: center;
        }

        .lowongan-footer {
            /* Baris total per lokasi */
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            border-top: 1px solid var(--border-color);
            background-color: #f1f5f9;
            font-weight: 600;
            font-size: .9rem;
        }

        /* ===== END OF CSS BARU ===== */

        /* ===== INTERACTIVE CHART HINTS ===== */
        .chart-hint {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 0.5rem;
            color: #1e40af;
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
            margin-bottom: 0.75rem;
        }

        .chart-hint i {
            color: #3b82f6;
        }

        /* Animate hint on hover */
        .card:hover .chart-hint {
            background-color: #dbeafe;
            border-color: #93c5fd;
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }

        /* Interactive cursor untuk chart */
        .interactive-chart {
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .interactive-chart:hover {
            transform: scale(1.02);
        }
    </style>
@endpush

{{-- Konten utama --}}
@section('content')
    <div class="container-fluid">

        {{-- ROW 0: KPI Ringkas (Modernized with Icons) --}}
        <div class="row g-4 mb-4">
            <div class="col-6 col-lg-2">
                <div class="kpi h-100">
                    <div class="icon icon-total"><i class="fa-solid fa-users"></i></div>
                    <div>
                        <div class="value">{{ isset($total) ? number_format($total) : '-' }}</div>
                        <div class="label">Total Karyawan</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="kpi h-100">
                    <div class="icon icon-female"><i class="fa-solid fa-venus"></i></div>
                    <div>
                        <div class="value">{{ isset($femalePct) ? number_format($femalePct, 1) . '%' : '-' }}</div>
                        <div class="label">% Perempuan</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <div class="kpi h-100">
                    <div class="icon icon-male"><i class="fa-solid fa-mars"></i></div>
                    <div>
                        <div class="value">
                            {{ isset($femalePct) && is_numeric($femalePct) ? number_format(100 - $femalePct, 1) . '%' : '-' }}
                        </div>
                        <div class="label">% Laki-laki</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="kpi h-100">
                    <div class="icon icon-age"><i class="fa-solid fa-cake-candles"></i></div>
                    <div>
                        <div class="value">{{ $avgAge ?? '-' }}</div>
                        <div class="label">Rata-rata Usia</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="kpi h-100">
                    <div class="icon icon-mk"><i class="fa-solid fa-business-time"></i></div>
                    <div>
                        <div class="value">{{ $avgMK ?? '-' }}</div>
                        <div class="label">Rata-rata Masa Kerja (Tahun)</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW 1 (BARU): Status & Gender --}}
        <div class="row g-4 mb-4">
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="card-title mb-0">Status Kepegawaian</h6>
                            <div class="text-end">
                                <small class="text-muted d-block">
                                    <i class="fa-solid fa-mouse-pointer me-1"></i>
                                    Klik untuk analisis detail
                                </small>
                            </div>
                        </div>
                        <div class="alert alert-info alert-dismissible fade show py-2 px-3 mb-3" role="alert"
                            style="font-size: 0.85rem;">
                            <i class="fa-solid fa-info-circle me-2"></i>
                            <strong>Tips:</strong> Klik pada segmen chart <strong>Organik</strong> untuk melihat analisis
                            mendalam
                            karyawan tetap,<br> atau klik <strong>Outsourcing</strong> untuk data karyawan kontrak/outsourcing.
                            <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" aria-label="Close"
                                style="font-size: 0.7rem;"></button>
                        </div>
                        <div style="position: relative; height: 220px;">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="card-title mb-0">Distribusi Gender</h6>
                            <small class="text-muted">
                                <i class="fa-solid fa-chart-bar me-1"></i>
                                Perbandingan organik vs outsourcing
                            </small>
                        </div>
                        <div style="position: relative; height: 220px;">
                            <canvas id="genderChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW 1.5 (BARU): Pendidikan --}}
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="card-title mb-0">Distribusi Pendidikan Terakhir</h6>
                            <small class="text-muted">
                                <i class="fa-solid fa-graduation-cap me-1"></i>
                                Komposisi tingkat pendidikan karyawan
                            </small>
                        </div>
                        <div style="position: relative; height: 220px;">
                            <canvas id="pendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW 3: Usia & Masa Kerja --}}
        <div class="row g-4 mb-4">
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">Sebaran Usia (tahun)</h6>
                        <div style="position: relative; height: 280px;">
                            <canvas id="usiaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">Sebaran Masa Kerja (tahun)</h6>
                        <div style="position: relative; height: 280px;">
                            <canvas id="mkChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW 3.5: TAMPILAN BARU JABATAN LOWONG --}}
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Rekapitulasi Jabatan Lowong Karyawan Organik PT Angkasa Pura Indonesia Regional 1</h6>
                        @if (isset($jabatanLowongGrouped) && $jabatanLowongGrouped->isNotEmpty())

                            <!-- KARTU TOTAL KESELURUHAN (BARU) -->
                            <div class="total-lowongan-card">
                                <span class="fw-semibold">Total Jabatan Lowong</span>
                                <div class="value">{{ $totalJabatanLowong }}</div>
                            </div>

                            <div class="lowongan-container">
                                {{-- Loop untuk setiap LOKASI --}}
                                @foreach ($jabatanLowongGrouped as $lokasi => $levels)
                                    <div class="lowongan-group">
                                        <div class="lowongan-header">
                                            <i class="fa-solid fa-map-marker-alt"></i>
                                            <span>{{ $lokasi }}</span>
                                        </div>
                                        <div class="lowongan-body">
                                            {{-- Loop untuk setiap LEVEL di dalam lokasi --}}
                                            @foreach ($levels as $lowong)
                                                <div class="lowongan-item clickable-item"
                                                    data-lokasi="{{ $lokasi }}" data-level="{{ $lowong->level }}"
                                                    data-total="{{ $lowong->total }}"
                                                    onclick="showDetailLowong('{{ $lokasi }}', '{{ $lowong->level }}', {{ $lowong->total }})"
                                                    style="cursor: pointer;"
                                                    title="Klik untuk melihat detail posisi kosong">
                                                    <span class="lowongan-level">{{ $lowong->level }}</span>
                                                    <span class="lowongan-badge">{{ $lowong->total }} <i
                                                            class="fa-solid fa-external-link-alt ms-1"
                                                            style="font-size: 0.7rem;"></i></span>
                                                </div>
                                            @endforeach
                                        </div>
                                        <!-- FOOTER TOTAL PER LOKASI (BARU) -->
                                        <div class="lowongan-footer">
                                            <span>Total</span>
                                            <span>{{ $levels->sum('total') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center p-4">
                                <i class="fa-solid fa-check-circle fa-2x text-success mb-2"></i>
                                <p class="text-muted mb-0">Tidak ada data jabatan yang lowong saat ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>


        {{-- ROW 4: Top Unit & Tabel Unit --}}
        <div class="row g-4">
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">Top 10 Unit Berdasarkan Jumlah Karyawan</h6>
                        <div style="position: relative; height: 420px;">
                            <canvas id="unitTopChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="card-title mb-0">Daftar Unit</h6>
                            <input id="searchUnit" class="form-control form-control-sm" style="max-width: 240px"
                                placeholder="Cari nama unit...">
                        </div>
                        <div class="table-responsive flex-grow-1" style="max-height: 420px; overflow:auto;">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>Unit</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="unitTableBody">
                                    @forelse(($unitTable ?? []) as $row)
                                        <tr>
                                            <td>{{ $row->unit }}</td>
                                            <td class="text-end">{{ number_format($row->total) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-muted p-4 text-center">Data unit belum
                                                tersedia.</td>
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

    {{-- Modal Detail Jabatan Lowong --}}
    <div class="modal fade" id="detailLowongModal" tabindex="-1" aria-labelledby="detailLowongModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailLowongModalLabel">
                        <i class="fa-solid fa-list-ul me-2"></i>
                        Detail Jabatan Lowong
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong><i class="fa-solid fa-map-marker-alt me-1"></i> Lokasi:</strong>
                            <span id="modalLokasi">-</span>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fa-solid fa-layer-group me-1"></i> Level:</strong>
                            <span id="modalLevel">-</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong><i class="fa-solid fa-users me-1"></i> Total Lowong:</strong>
                            <span class="badge bg-danger" id="modalTotal">0</span>
                        </div>
                    </div>

                    <div class="loading-container" id="loadingDetail" style="display: none;">
                        <div class="text-center p-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Memuat detail posisi kosong...</p>
                        </div>
                    </div>

                    <div id="detailContent">
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="fa-solid fa-briefcase me-2"></i>
                            Daftar Posisi Yang Kosong
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 15%;">Kode Jabatan</th>
                                        <th style="width: 35%;">Nama Jabatan</th>
                                        <th style="width: 25%;">Unit</th>
                                        <th style="width: 15%;" class="text-center">Formasi</th>
                                        <th style="width: 10%;" class="text-center">Terisi</th>
                                    </tr>
                                </thead>
                                <tbody id="detailTableBody">
                                    <tr>
                                        <td colspan="5" class="text-center text-muted p-3">
                                            Klik item di dashboard untuk melihat detail
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-times me-1"></i> Tutup
                    </button>
                    <button type="button" class="btn btn-primary" id="btnExportDetail">
                        <i class="fa-solid fa-download me-1"></i> Export Excel
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Script halaman --}}
@push('body-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.ChartDataLabels) {
                Chart.register(ChartDataLabels);
            }
            const palette = ['#4f46e5', '#db2777', '#f97316', '#16a34a', '#8b5cf6', '#0891b2', '#f43f5e', '#78350f',
                '#0d9488'
            ];
            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.color = '#64748b';
            Chart.defaults.plugins.legend.position = 'bottom';
            Chart.defaults.plugins.legend.labels.usePointStyle = true;
            Chart.defaults.plugins.legend.labels.padding = 20;
            Chart.defaults.plugins.tooltip.backgroundColor = '#1e293b';
            Chart.defaults.plugins.tooltip.titleFont = {
                weight: 'bold',
                size: 14
            };
            Chart.defaults.plugins.tooltip.bodyFont = {
                size: 12
            };
            Chart.defaults.plugins.tooltip.padding = 10;
            Chart.defaults.plugins.tooltip.cornerRadius = 8;
            Chart.defaults.plugins.tooltip.displayColors = false;

            const statusLabels = @json($labels ?? []);
            const statusData = @json($data ?? []);
            const statusCtx = document.getElementById('statusChart')?.getContext('2d');
            if (statusCtx) {
                new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: statusLabels,
                        datasets: [{
                            label: 'Jumlah Karyawan',
                            data: statusData,
                            backgroundColor: palette,
                            borderWidth: 0,
                            hoverBorderColor: '#fff',
                            hoverBorderWidth: 2,
                            hoverOffset: 15
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '60%',
                        plugins: {
                            legend: {
                                position: 'right'
                            },
                            datalabels: {
                                anchor: 'center',
                                align: 'center',
                                color: '#fff',
                                formatter: (value, ctx) => {
                                    const total = ctx.chart.data.datasets[0].data.reduce((a, b) => a +
                                        b, 0);
                                    const pct = total ? (value / total * 100).toFixed(1) : 0;
                                    if (pct < 5) return '';
                                    return `${value}\n(${pct}%)`;
                                },
                                font: {
                                    weight: '700',
                                    size: 11
                                }
                            }
                        },
                        onClick: (evt, elements) => {
                            if (elements.length > 0) {
                                const chart = elements[0].element.$context.chart;
                                const index = elements[0].index;
                                const label = chart.data.labels[index].toLowerCase().trim();
                                if (label.includes('outsourcing')) {
                                    window.location.href = '/analitikoutsourcing';
                                } else {
                                    window.location.href = '/analitikorganic';
                                }
                            }
                        },
                        onHover: (event, chartElement) => {
                            const canvas = event.native.target;
                            canvas.style.cursor = chartElement[0] ? 'pointer' : 'default';
                        }
                    }
                });
            }

            const genderGroupLabels = @json($genderGroupLabels ?? []);
            const genderGroupOS = @json($genderGroupOS ?? []);
            const genderGroupOrganic = @json($genderGroupOrganic ?? []);
            const hasGenderGrouped = Array.isArray(genderGroupLabels) && genderGroupLabels.length > 0;
            const genderLabels = @json($genderLabels ?? []);
            const genderData = @json($genderData ?? []);
            const pendLabels = @json($pendLabels ?? []);
            const pendData = @json($pendData ?? []);
            const usiaLabels = @json($usiaLabels ?? []);
            const usiaData = @json($usiaData ?? []);
            const mkLabels = @json($mkLabels ?? []);
            const mkData = @json($mkData ?? []);
            const unitTopLabels = @json($unitTopLabels ?? []);
            const unitTopData = @json($unitTopData ?? []);

            function makeChart(elId, type, labels, data, opts = {}) {
                const el = document.getElementById(elId);
                if (!el || !labels || !labels.length) return;
                const barDatalabels = window.ChartDataLabels ? {
                    anchor: 'end',
                    align: 'end',
                    color: '#334155',
                    formatter: (v) => v > 0 ? v : '',
                    font: {
                        weight: '600',
                        size: 10
                    }
                } : false;
                return new Chart(el.getContext('2d'), {
                    type: type,
                    data: {
                        labels: labels,
                        datasets: [{
                            label: opts.label || 'Jumlah',
                            data: data,
                            backgroundColor: opts.horizontal ? palette[0] : palette,
                            borderColor: opts.horizontal ? palette[0] : palette,
                            borderWidth: type === 'line' ? 2 : 0,
                            borderRadius: 4,
                            barPercentage: 0.7,
                            categoryPercentage: 0.8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: opts.horizontal ? 'y' : 'x',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: true
                            },
                            datalabels: barDatalabels
                        },
                        scales: (type === 'bar') ? {
                            x: {
                                grid: {
                                    display: opts.horizontal,
                                    drawBorder: false
                                },
                                ticks: {
                                    autoSkip: true,
                                    maxRotation: 0
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    display: !opts.horizontal,
                                    color: '#e2e8f0',
                                    drawBorder: false
                                },
                                ticks: {
                                    precision: 0
                                }
                            }
                        } : {}
                    }
                });
            }

            const genderCanvas = document.getElementById('genderChart');
            if (genderCanvas && hasGenderGrouped) {
                new Chart(genderCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: genderGroupLabels,
                        datasets: [{
                                label: 'Organik',
                                data: genderGroupOrganic,
                                backgroundColor: palette[0],
                                borderRadius: 4
                            },
                            {
                                label: 'Outsourcing',
                                data: genderGroupOS,
                                backgroundColor: palette[1],
                                borderRadius: 4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top'
                            },
                            tooltip: {
                                enabled: true
                            },
                            // ---- MODIFIKASI UNTUK MENAMPILKAN TOTAL DI DALAM BAR ----
                            datalabels: {
                                display: true, // 1. Aktifkan label
                                color: 'white', // 2. Atur warna font menjadi putih
                                anchor: 'center', // 3. Posisikan di tengah bar
                                align: 'center', // 4. Ratakan teks di tengah
                                font: {
                                    weight: 'bold', // 5. Buat font tebal agar mudah dibaca
                                    size: 12
                                },
                                // 6. Fungsi untuk menampilkan angka hanya jika nilainya lebih dari 0
                                formatter: (value) => {
                                    return value > 0 ? value : '';
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                },
                                grid: {
                                    color: '#e2e8f0',
                                    drawBorder: false
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            } else if (genderCanvas) {
                const pieGenderCtx = genderCanvas.getContext('2d');
                new Chart(pieGenderCtx, {
                    type: 'pie',
                    data: {
                        labels: genderLabels,
                        datasets: [{
                            data: genderData,
                            backgroundColor: [palette[0], palette[1]],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right'
                            }
                        }
                    }
                });
            }

            const pendGroupLabels = @json($pendGroupLabels ?? []);
            const pendGroupOS = @json($pendGroupOS ?? []);
            const pendGroupOrganic = @json($pendGroupOrganic ?? []);
            const hasPendGrouped = Array.isArray(pendGroupLabels) && pendGroupLabels.length > 0;

            const pendCanvas = document.getElementById('pendChart');

            // 2. Cek apakah data terkelompok ada, jika ya, buat grouped bar chart
            if (pendCanvas && hasPendGrouped) {
                new Chart(pendCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: pendGroupLabels,
                        datasets: [{
                                label: 'Organik',
                                data: pendGroupOrganic,
                                backgroundColor: palette[0], // Warna dari palet (indigo)
                                borderRadius: 4
                            },
                            {
                                label: 'Outsourcing',
                                data: pendGroupOS,
                                backgroundColor: palette[1], // Warna dari palet (pink)
                                borderRadius: 4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top'
                            },
                            tooltip: {
                                enabled: true
                            },
                            // ---- MODIFIKASI UNTUK MENAMPILKAN TOTAL DI DALAM BAR ----
                            datalabels: {
                                display: true, // 1. Aktifkan label
                                color: 'white', // 2. Atur warna font menjadi putih
                                anchor: 'center', // 3. Posisikan di tengah bar
                                align: 'center', // 4. Ratakan teks di tengah
                                font: {
                                    weight: 'bold', // 5. Buat font tebal agar mudah dibaca
                                    size: 12
                                },
                                // 6. Fungsi untuk menampilkan angka hanya jika nilainya lebih dari 0
                                formatter: (value) => {
                                    return value > 0 ? value : '';
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                },
                                grid: {
                                    color: '#e2e8f0',
                                    drawBorder: false
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            } else if (pendCanvas) {
                // 3. Jika data terkelompok tidak ada, gunakan chart lama sebagai fallback
                makeChart('pendChart', 'bar', pendLabels, pendData);
            }
            makeChart('usiaChart', 'bar', usiaLabels, usiaData);
            makeChart('mkChart', 'bar', mkLabels, mkData);
            makeChart('unitTopChart', 'bar', unitTopLabels, unitTopData, {
                horizontal: true
            });

            const searchUnit = document.getElementById('searchUnit');
            const tbody = document.getElementById('unitTableBody');
            if (searchUnit && tbody) {
                searchUnit.addEventListener('input', function() {
                    const q = this.value.toLowerCase();
                    [...tbody.querySelectorAll('tr')].forEach(tr => {
                        const unit = (tr.children[0]?.textContent || '').toLowerCase();
                        tr.style.display = unit.includes(q) ? '' : 'none';
                    });
                });
            }
        });

        // Function untuk menampilkan detail jabatan lowong
        function showDetailLowong(lokasi, level, total) {
            // Set data ke modal
            document.getElementById('modalLokasi').textContent = lokasi;
            document.getElementById('modalLevel').textContent = level;
            document.getElementById('modalTotal').textContent = total;

            // Show loading
            document.getElementById('loadingDetail').style.display = 'block';
            document.getElementById('detailContent').style.display = 'none';

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('detailLowongModal'));
            modal.show();

            // AJAX request untuk mendapatkan detail
            fetch(`/dashboard/jabatan-lowong-detail?lokasi=${encodeURIComponent(lokasi)}&level=${encodeURIComponent(level)}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Hide loading
                    document.getElementById('loadingDetail').style.display = 'none';
                    document.getElementById('detailContent').style.display = 'block';

                    const tableBody = document.getElementById('detailTableBody');

                    if (data.success && data.data.length > 0) {
                        let html = '';
                        data.data.forEach(item => {
                            const lowongCount = item.formasi_count - item.karyawan_count;
                            html += `
                            <tr>
                                <td><code>${item.kode_jabatan}</code></td>
                                <td>${item.jabatan}</td>
                                <td>${item.unit}</td>
                                <td class="text-center"><span class="badge bg-info">${item.formasi_count}</span></td>
                                <td class="text-center"><span class="badge bg-success">${item.karyawan_count}</span></td>
                            </tr>
                        `;
                        });
                        tableBody.innerHTML = html;
                    } else {
                        tableBody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center text-muted p-3">
                                <i class="fa-solid fa-exclamation-circle me-2"></i>
                                Tidak ada data detail untuk lokasi dan level ini
                            </td>
                        </tr>
                    `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('loadingDetail').style.display = 'none';
                    document.getElementById('detailContent').style.display = 'block';

                    document.getElementById('detailTableBody').innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center text-danger p-3">
                            <i class="fa-solid fa-exclamation-triangle me-2"></i>
                            Terjadi kesalahan saat memuat data
                        </td>
                    </tr>
                `;
                });
        }

        // Export detail function
        document.getElementById('btnExportDetail')?.addEventListener('click', function() {
            const lokasi = document.getElementById('modalLokasi').textContent;
            const level = document.getElementById('modalLevel').textContent;

            window.location.href =
                `/dashboard/jabatan-lowong-export?lokasi=${encodeURIComponent(lokasi)}&level=${encodeURIComponent(level)}`;
        });
    </script>
@endpush
