<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaizen Analytics - Red & White Focus</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --primary-red: #8b0000;
            --secondary-red: #b91c1c;
            --text-dark: #1e293b;
            --accent-gold: #fbbf24;
        }

        *, *::before, *::after { box-sizing: border-box; }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: var(--bg-body);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
        }

        .dashboard-container {
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 16px 20px;
            background: white;
            overflow: hidden;
        }

        .btn-upload {
            background: var(--primary-red);
            color: white;
            border: none;
            padding: 6px 16px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            min-width: 110px;
            flex-shrink: 0;
        }

        .top-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
            height: 90px;
            border-bottom: 2px solid var(--bg-body);
            margin-bottom: 12px;
        }

        .header {
            text-align: center;
            flex: 1;
        }

        .header h1 {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--primary-red);
            margin: 0 0 8px 0;
        }

        .peak-mini-card {
            background: linear-gradient(135deg, var(--primary-red), var(--secondary-red));
            padding: 8px 18px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 15px;
            color: white;
            white-space: nowrap;
            min-width: 180px;
            max-width: 180px;
            overflow: hidden;
        }
        .peak-mini-card #topDeptName {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 100px;
        }

        .peak-mini-card .val { font-size: 1.4rem; font-weight: 800; color: var(--accent-gold); }

        .row-horizontal {
            display: flex;
            gap: 20px;
            align-items: stretch;
            width: 100%;
            flex-shrink: 0;
        }

        .k-card {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 22px;
            height: 380px;
            border: 1px solid #e2e8f0;
            display: flex;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            flex-direction: column;
            flex-shrink: 0;
        }

        .card-radar-main { flex: 1.5; border-top: 4px solid var(--primary-red); }
        .card-radar-sub { flex: 1; }
        .card-employee-main { flex: 1.2; }

        .card-label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 18px; display: flex; align-items: center; gap: 8px; }
        .card-label::before { content: ''; width: 4px; height: 14px; background: var(--primary-red); border-radius: 2px; }
        .chart-box { flex: 1; position: relative; min-height: 0; }

        .table-custom thead { background-color: var(--bg-body); font-size: 0.7rem; }
        .table-custom td { font-size: 0.85rem; vertical-align: middle; }

        .btn-detail-perf {
            background: var(--primary-red);
            color: white;
            border: none;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            cursor: pointer;
            margin-left: auto;
        }
        .btn-detail-perf:hover { background: var(--secondary-red); }
        .table-custom td { font-size: 0.85rem; vertical-align: middle; }
    </style>
</head>
<body>

<div class="dashboard-container">
    <div class="top-bar">
        <button class="btn-upload" onclick="promptImport()">+ Import Data</button>

        <div class="header">
            <h1>KAIZEN <span style="font-weight:300">ANALYTICS SYSTEM</span></h1>
            <div class="d-flex justify-content-center align-items-center gap-2 mt-2">
                <span>Fiscal Year:</span>
                <select id="filterTahun" class="form-select form-select-sm" style="width: 100px;" onchange="loadData()">
                    <option value="2025">2025</option>
                    <option value="2026" selected>2026</option>
                </select>
                <div class="btn-group shadow-sm">
                    <button type="button" class="btn btn-outline-danger px-3" onclick="pilihBulan(4, this)">Apr</button>
                    <button type="button" class="btn btn-outline-danger px-3" onclick="pilihBulan(5, this)">Mei</button>
                    <button type="button" class="btn btn-outline-danger px-3" onclick="pilihBulan(6, this)">Jun</button>
                    <button type="button" class="btn btn-outline-danger px-3" onclick="pilihBulan(7, this)">Jul</button>
                    <button type="button" class="btn btn-outline-danger px-3" onclick="pilihBulan(8, this)">Agu</button>
                    <button type="button" class="btn btn-outline-danger px-3" onclick="pilihBulan(9, this)">Sep</button>
                    <button type="button" class="btn btn-outline-danger px-3" onclick="pilihBulan(10, this)">Okt</button>
                    <button type="button" class="btn btn-outline-danger px-3" onclick="pilihBulan(11, this)">Nov</button>
                    <button type="button" class="btn btn-outline-danger px-3" onclick="pilihBulan(12, this)">Des</button>
                    <button type="button" class="btn btn-outline-danger px-3" onclick="pilihBulan(1, this)">Jan</button>
                    <button type="button" class="btn btn-outline-danger px-3" onclick="pilihBulan(2, this)">Feb</button>
                    <button type="button" class="btn btn-outline-danger px-3" onclick="pilihBulan(3, this)">Mar</button>
                </div>
            </div>
        </div>

        <div class="peak-mini-card">
            <div>
                <div style="font-size: 0.6rem; font-weight: 800; opacity: 0.8;">PEAK PERFORMANCE</div>
                <div id="topDeptName" style="font-size: 0.75rem; font-weight: 600;">LOADING...</div>
            </div>
            <div class="val" id="topScore" style="font-size: 1.4rem; font-weight: 800; color: var(--accent-gold);">0%</div>
        </div>
    </div>

    <!-- ROW 1: 4 Chart Cards sejajar -->
    <div class="row-horizontal">
        <div class="k-card card-radar-main">
            <div class="card-label">Awareness Ratio</div>
            <div class="chart-box" id="container-dept"><canvas id="chartDeptRadar"></canvas></div>
        </div>
        <div class="k-card card-radar-sub">
            <div class="card-label">Category Tendency</div>
            <div class="chart-box" id="container-radar"><canvas id="chartRadar"></canvas></div>
        </div>
        <div class="k-card card-employee-main">
            <div class="card-label">Top Kaizen Idea</div>
            <div class="chart-box" id="container-emp"><canvas id="chartEmployee"></canvas></div>
        </div>
        <div class="k-card card-employee-main">
            <div class="card-label" style="justify-content: space-between;">
                <span>Top Performance</span>
                <button class="btn-detail-perf" onclick="bukaModalTopPerformance()">&#9776; Detail</button>
            </div>
            <div class="chart-box" id="container-top-performance"><canvas id="chartTopPerformance"></canvas></div>
        </div>
    </div>

    <!-- ROW 3: Detail Table -->
    <div class="k-card" style="width: 100%; margin-top: 20px; height: 280px; flex-shrink: 0;">
        <div class="card-label" style="margin-bottom: 10px;">DATA DETAIL KAIZEN</div>
        <div class="table-responsive" style="flex: 1; overflow-y: auto; min-height: 0;">
            <table class="table table-hover table-custom">
                <thead style="position: sticky; top: 0; z-index: 2; background: var(--bg-body);">
                    <tr>
                        <th style="width:40px">No</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Employee</th>
                        <th>Dept</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody id="table-detail-kaizen"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Detail Karyawan -->
<div class="modal fade" id="modalDetailKaryawan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden;">
            <div class="modal-header" id="modalHeaderWarna" style="background: var(--primary-red); color: white; transition: background 0.3s ease;">
                <h5 class="modal-title" id="judulModalDetail" style="font-weight: 800; font-size: 1rem;">PERFORMA SELURUH KARYAWAN</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="max-height: 480px; overflow-y: auto;">
                <div id="modalRekapAtas" class="p-3 bg-light border-bottom d-flex justify-content-around text-center fw-bold text-dark" style="font-size: 0.85rem;">
                    <div>
                        <div class="text-muted small" style="font-size: 0.65rem;">JUMLAH KARYAWAN</div>
                        <span id="modalTotalKaryawan" class="fs-5 text-dark">0</span> Orang
                    </div>
                    <div class="border-end"></div>
                    <div>
                        <div class="text-muted small" style="font-size: 0.65rem;">KARYAWAN IKUT</div>
                        <span id="modalKaryawanIkut" class="fs-5 text-danger">0</span> Orang
                    </div>
                    <div class="border-end"></div>
                    <div>
                        <div class="text-muted small" style="font-size: 0.65rem;">PERSENTASE PARTISIPASI</div>
                        <span id="modalPersentase" class="fs-5 text-success">0%</span>
                    </div>
                </div>
                <table class="table table-hover table-custom m-0">
                    <thead class="sticky-top bg-white" style="top: 0; z-index: 10;">
                        <tr id="modalTableHeadRow"></tr>
                    </thead>
                    <tbody id="tableBodyKaryawan"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Modal Top Performance Detail -->
<div class="modal fade" id="modalTopPerformance" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden;">
            <div class="modal-header" style="background: var(--primary-red); color: white;">
                <h5 class="modal-title" id="judulModalTopPerf" style="font-weight: 800; font-size: 1rem;">TOP PERFORMANCE - SEMUA KARYAWAN</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="max-height: 500px; overflow-y: auto;">
                <div class="p-3 bg-light border-bottom d-flex justify-content-around text-center fw-bold text-dark" style="font-size: 0.85rem;">
                    <div>
                        <div class="text-muted small" style="font-size: 0.65rem;">TOTAL KARYAWAN SUBMIT</div>
                        <span id="perfTotalSubmit" class="fs-5 text-danger">0</span> Orang
                    </div>
                    <div class="border-end"></div>
                    <div>
                        <div class="text-muted small" style="font-size: 0.65rem;">TOTAL SUBMISSION</div>
                        <span id="perfTotalSubmisi" class="fs-5 text-dark">0</span> Lembar
                    </div>
                    <div class="border-end"></div>
                    <div>
                        <div class="text-muted small" style="font-size: 0.65rem;">TOTAL NILAI</div>
                        <span id="perfTotalNilai" class="fs-5 text-success">0</span> Poin
                    </div>
                </div>
                <table class="table table-hover table-custom m-0">
                    <thead class="sticky-top bg-white" style="top: 0; z-index: 10;">
                        <tr>
                            <th class="ps-4" style="width: 10%">Rank</th>
                            <th style="width: 45%">Nama Karyawan</th>
                            <th class="text-center" style="width: 20%">Jumlah Submit</th>
                            <th class="text-center" style="width: 25%">Total Nilai</th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyTopPerf"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
async function promptImport() {
    const { value: formValues } = await Swal.fire({
        title: 'Admin Access',
        html:
            '<input id="swal-password" type="password" class="swal2-input" placeholder="Masukkan Password">' +
            '<input id="swal-file" type="file" class="swal2-file" accept=".xlsx, .xls">',
        confirmButtonText: 'Proses Import',
        showCancelButton: true,
        preConfirm: () => {
            const password = document.getElementById('swal-password').value;
            const file = document.getElementById('swal-file').files[0];
            if (password !== "kaizen123") {
                Swal.showValidationMessage('Password Salah!');
                return false;
            }
            if (!file) {
                Swal.showValidationMessage('Pilih file dulu!');
                return false;
            }
            return { file: file };
        }
    });
    if (formValues) uploadData(formValues.file);
}

function uploadData(file) {
    let formData = new FormData();
    formData.append('file_excel', file);
    Swal.fire({ title: 'Mengunggah...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
    fetch('proses_import.php', { method: 'POST', body: formData })
        .then(response => response.text())
        .then(result => { Swal.fire({ title: 'Hasil Import', html: result, icon: 'info' }); })
        .catch(error => { Swal.fire('Error!', 'Gagal menghubungi server', 'error'); });
}

// Plugin: tampilkan angka score di atas setiap bar
const scoreTopPlugin = {
    id: 'scoreTopPlugin',
    afterDatasetsDraw(chart) {
        const { ctx, data, scales: { x, y } } = chart;
        ctx.save();
        data.datasets[0].data.forEach((value, i) => {
            const bar = chart.getDatasetMeta(0).data[i];
            ctx.fillStyle = '#1e293b';
            ctx.font = 'bold 11px Inter, sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'bottom';
            ctx.fillText(value, bar.x, bar.y - 4);
        });
        ctx.restore();
    }
};

window.activeCharts = {};
let masterEmployeeData = [];
let masterTopPerfData = [];
let bulanAktif = 8;

function pilihBulan(angkaBulan, elemenTombol) {
    bulanAktif = angkaBulan;
    const semuaTombol = elemenTombol.parentElement.querySelectorAll('button');
    semuaTombol.forEach(btn => {
        btn.classList.remove('btn-danger', 'active');
        btn.classList.add('btn-outline-danger');
    });
    elemenTombol.classList.remove('btn-outline-danger');
    elemenTombol.classList.add('btn-danger', 'active');
    window.scrollTo({ top: 0, behavior: 'smooth' });
    loadData();
}

function resetCanvas(containerId, canvasId) {
    if (window.activeCharts[canvasId]) {
        window.activeCharts[canvasId].destroy();
        delete window.activeCharts[canvasId];
    }
    // Reuse canvas yang sudah ada, jangan replace innerHTML agar tidak reflow
    const canvas = document.getElementById(canvasId);
    return canvas.getContext('2d');
}

function filterKaryawanKeModal(namaDept) {
    document.getElementById('judulModalDetail').innerText = `BREAKDOWN DETAIL KARYAWAN: ${namaDept.toUpperCase()}`;
    document.getElementById('modalHeaderWarna').style.background = '#b91c1c';
    document.getElementById('modalRekapAtas').style.setProperty('display', 'flex', 'important');
    document.getElementById('modalTableHeadRow').innerHTML = `
        <th class="ps-4" style="width: 15%">Rank</th>
        <th style="width: 45%">Nama Karyawan</th>
        <th class="text-center" style="width: 20%">Kontribusi Kaizen</th>
        <th class="text-center" style="width: 20%">Departemen</th>
    `;
    document.getElementById('modalTotalKaryawan').innerText = '-';
    document.getElementById('modalKaryawanIkut').innerText = '-';
    document.getElementById('modalPersentase').innerText = '-%';
    const tableBody = document.getElementById('tableBodyKaryawan');
    tableBody.innerHTML = `<tr><td colspan="4" class="text-center py-4"><div class="spinner-border text-danger spinner-border-sm"></div> Memuat data ${namaDept}...</td></tr>`;
    const modalElement = new bootstrap.Modal(document.getElementById('modalDetailKaryawan'));
    modalElement.show();
    const thn = document.getElementById('filterTahun').value;
    fetch(`get_detail_karyawan.php?dept=${encodeURIComponent(namaDept)}&bulan=${bulanAktif}&tahun=${thn}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('modalTotalKaryawan').innerText = data.rekap.total_karyawan;
            document.getElementById('modalKaryawanIkut').innerText = data.rekap.karyawan_ikut;
            document.getElementById('modalPersentase').innerText = data.rekap.persentase + '%';
            tableBody.innerHTML = '';
            if (!data.karyawan || data.karyawan.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="4" class="text-center text-muted py-4">Belum ada kiriman lembar Kaizen.</td></tr>`;
                return;
            }
            data.karyawan.forEach((item, index) => {
                tableBody.innerHTML += `
                    <tr>
                        <td class="ps-4"><span class="badge ${index < 3 ? 'bg-danger' : 'bg-secondary'}">${index + 1}</span></td>
                        <td class="fw-semibold">${item.nama_karyawan}</td>
                        <td class="text-center fw-bold">${item.total_kaizen} Lembar</td>
                        <td class="text-center"><span class="text-success" style="font-size: 0.7rem;">● ${namaDept}</span></td>
                    </tr>`;
            });
        });
}

function resetModalKeSemuaKaryawan() {
    document.getElementById('judulModalDetail').innerText = "PERFORMA JUARA SELURUH KARYAWAN (LEADERBOARD)";
    document.getElementById('modalHeaderWarna').style.background = 'var(--primary-red)';
    document.getElementById('modalRekapAtas').style.setProperty('display', 'none', 'important');
    document.getElementById('modalTableHeadRow').innerHTML = `
        <th class="ps-4" style="width: 15%">Rank</th>
        <th style="width: 55%">Nama Karyawan</th>
        <th class="text-center" style="width: 30%">Skor Penilaian</th>
    `;
    const tableBody = document.getElementById('tableBodyKaryawan');
    tableBody.innerHTML = '';
    masterEmployeeData.forEach((item, index) => {
        tableBody.innerHTML += `
            <tr>
                <td class="ps-4"><span class="badge bg-danger">${index + 1}</span></td>
                <td class="fw-semibold">${item.name}</td>
                <td class="text-center fw-bold">${item.val} Poin</td>
            </tr>`;
    });
    const modalElement = new bootstrap.Modal(document.getElementById('modalDetailKaryawan'));
    modalElement.show();
}

async function loadData() {
    try {
        const thn = document.getElementById('filterTahun').value;
        const res = await fetch(`get_data.php?bulan=${bulanAktif}&tahun=${thn}`);
        const data = await res.json();

        // Reset data tiap load agar bulan kosong tidak pakai data lama
        masterEmployeeData = [];
        masterTopPerfData = [];

        // ── AWARENESS RATIO (Radar Dept) ──────────────────────────────
        const processedDeptValues = data.dept.labels.map((_, index) => {
            let ikut = data.dept.karyawan_ikut[index] || 0;
            let total = data.dept.total_karyawan[index] || 1;
            return parseFloat(((ikut / total) * 100).toFixed(1));
        });

        const ctxDept = resetCanvas('container-dept', 'chartDeptRadar');
        window.activeCharts['chartDeptRadar'] = new Chart(ctxDept, {
            type: 'radar',
            data: {
                labels: data.dept.labels,
                datasets: [{
                    label: 'Participation %',
                    data: processedDeptValues,
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.2)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: { r: { min: 0, max: 100, ticks: { stepSize: 20, callback: v => v + '%' } } },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let idx = context.dataIndex;
                                return `${context.label}: ${processedDeptValues[idx]}% (${data.dept.karyawan_ikut[idx]}/${data.dept.total_karyawan[idx]})`;
                            }
                        }
                    }
                },
                onClick: (event, elements, chart) => {
                    const canvasPosition = Chart.helpers.getRelativePosition(event, chart);
                    const scale = chart.scales.r;
                    let labelTerpilih = null;
                    if (scale._pointLabelItems) {
                        scale._pointLabelItems.forEach((item, index) => {
                            if (canvasPosition.x >= item.left && canvasPosition.x <= item.right && canvasPosition.y >= item.top && canvasPosition.y <= item.bottom) {
                                labelTerpilih = chart.data.labels[index];
                            }
                        });
                    }
                    if (labelTerpilih) filterKaryawanKeModal(labelTerpilih);
                }
            }
        });

        // ── CATEGORY TENDENCY (Radar Kategori) ───────────────────────
        const rawRadarValues = data.radar.values || [];
        const cleanRadarValues = rawRadarValues.map(val => Math.round(val));
        const totalAktual = cleanRadarValues.reduce((a, b) => a + b, 0);
        const processedRadarValues = cleanRadarValues.map(val => totalAktual > 0 ? parseFloat(((val / totalAktual) * 100).toFixed(1)) : 0);

        const ctxRadar = resetCanvas('container-radar', 'chartRadar');
        window.activeCharts['chartRadar'] = new Chart(ctxRadar, {
            type: 'radar',
            data: {
                labels: data.radar.labels,
                datasets: [{
                    label: 'Contribution Share',
                    data: processedRadarValues,
                    borderColor: '#8b0000',
                    backgroundColor: 'rgba(139, 0, 0, 0.4)',
                    borderWidth: 3,
                    fill: true
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: { r: { min: 0, max: 100, ticks: { stepSize: 20, callback: v => v + '%' } } },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let index = context.dataIndex;
                                return `${context.label}: ${cleanRadarValues[index]} / ${totalAktual} (${processedRadarValues[index]}%)`;
                            }
                        }
                    }
                }
            }
        });

        // ── TOP KAIZEN IDEA (Bar - skor tertinggi per submission) ─────
        if (data.employee && data.employee.labels && data.employee.labels.length > 0) {
            const ctxEmp = resetCanvas('container-emp', 'chartEmployee');
            const topFiveValues = data.employee.values.slice(0, 5);
            window.activeCharts['chartEmployee'] = new Chart(ctxEmp, {
                type: 'bar',
                data: {
                    labels: data.employee.labels.slice(0, 5),
                    datasets: [{
                        data: topFiveValues,
                        backgroundColor: '#8b0000',
                        borderRadius: 5
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        x: { display: false },
                        y: { beginAtZero: true, suggestedMax: Math.max(...topFiveValues) + 15 }
                    },
                    plugins: {
                        legend: { display: false },
                        datalabels: {
                            anchor: 'start',
                            align: 'end',
                            color: '#ffffff',
                            font: { weight: 'bold', size: 10 },
                            rotation: -90,
                            formatter: (value, context) => context.chart.data.labels[context.dataIndex]
                        }
                    }
                },
                plugins: [ChartDataLabels, {
                    id: 'scoreAboveEmp',
                    afterDraw(chart) {
                        const ctx = chart.ctx;
                        chart.getDatasetMeta(0).data.forEach((bar, i) => {
                            const val = chart.data.datasets[0].data[i];
                            ctx.save();
                            ctx.fillStyle = '#1e293b';
                            ctx.font = 'bold 11px Inter, sans-serif';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'bottom';
                            ctx.fillText(val, bar.x, bar.y - 2);
                            ctx.restore();
                        });
                    }
                }]
            });
            masterEmployeeData = data.employee.labels.map((name, index) => ({ name: name, val: data.employee.values[index] }));
        } else {
            const ctxEmp = resetCanvas('container-emp', 'chartEmployee');
            window.activeCharts['chartEmployee'] = new Chart(ctxEmp, {
                type: 'bar',
                data: { labels: [], datasets: [{ data: [], backgroundColor: '#8b0000' }] },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        x: { display: false },
                        y: { beginAtZero: true, suggestedMax: 30 }
                    },
                    plugins: { legend: { display: false }, datalabels: { display: false } }
                },
                plugins: [ChartDataLabels]
            });
        }

        // ── TOP PERFORMANCE (Bar - SUM nilai, top 5, style sama Top Kaizen Idea) ──
        if (data.top_performance && data.top_performance.labels && data.top_performance.labels.length > 0) {
            // Simpan semua data untuk modal
            masterTopPerfData = data.top_performance.labels.map((name, i) => ({
                name: name,
                nilai: data.top_performance.values[i],
                submit: data.top_performance.submissions[i]
            }));

            const ctxTop = resetCanvas('container-top-performance', 'chartTopPerformance');
            const topLabels = data.top_performance.labels.slice(0, 5);
            const topValues = data.top_performance.values.slice(0, 5);
            const topSubmissions = data.top_performance.submissions.slice(0, 5);

            window.activeCharts['chartTopPerformance'] = new Chart(ctxTop, {
                type: 'bar',
                data: {
                    labels: topLabels,
                    datasets: [{
                        data: topValues,
                        backgroundColor: '#8b0000',
                        borderRadius: 5
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        x: { display: false },
                        y: { beginAtZero: true, suggestedMax: Math.max(...topValues) + 15 }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                title: (items) => topLabels[items[0].dataIndex],
                                label: (item) => [
                                    `Total Nilai: ${topValues[item.dataIndex]}`,
                                    `Jumlah Submit: ${topSubmissions[item.dataIndex]}x`
                                ]
                            }
                        },
                        datalabels: {
                            anchor: 'start',
                            align: 'end',
                            color: '#ffffff',
                            font: { weight: 'bold', size: 10 },
                            rotation: -90,
                            formatter: (value, context) => context.chart.data.labels[context.dataIndex]
                        }
                    }
                },
                plugins: [ChartDataLabels, {
                    id: 'scoreAbovePerf',
                    afterDraw(chart) {
                        const ctx = chart.ctx;
                        chart.getDatasetMeta(0).data.forEach((bar, i) => {
                            const val = chart.data.datasets[0].data[i];
                            ctx.save();
                            ctx.fillStyle = '#1e293b';
                            ctx.font = 'bold 11px Inter, sans-serif';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'bottom';
                            ctx.fillText(val, bar.x, bar.y - 2);
                            ctx.restore();
                        });
                    }
                }]
            });
        } else {
            const ctxTop = resetCanvas('container-top-performance', 'chartTopPerformance');
            window.activeCharts['chartTopPerformance'] = new Chart(ctxTop, {
                type: 'bar',
                data: { labels: [], datasets: [{ data: [], backgroundColor: '#8b0000' }] },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        x: { display: false },
                        y: { beginAtZero: true, suggestedMax: 30 }
                    },
                    plugins: { legend: { display: false }, datalabels: { display: false } }
                },
                plugins: [ChartDataLabels]
            });
        }

        // ── PEAK PERFORMANCE BADGE ────────────────────────────────────

        if (processedDeptValues.length > 0) {
            const maxVal = Math.max(...processedDeptValues);
            const idx = processedDeptValues.indexOf(maxVal);
            document.getElementById('topScore').innerText = maxVal + '%';
            document.getElementById('topDeptName').innerText = data.dept.labels[idx];
        }

        // ── DETAIL TABLE ──────────────────────────────────────────────
        const tb = document.getElementById('table-detail-kaizen');
        if (tb) {
            tb.innerHTML = '';
            if (data.kaizen_list) {
                data.kaizen_list.forEach((item, idx) => {
                    tb.innerHTML += `<tr>
                        <td style="color:#94a3b8;font-size:0.75rem">${idx + 1}</td>
                        <td>${item.judul_kaizen}</td>
                        <td>${item.nama_category}</td>
                        <td>${item.nama_karyawan}</td>
                        <td>${item.nama_dept}</td>
                        <td><strong>${item.total_score}</strong></td>
                    </tr>`;
                });
            }
        }

    } catch (e) { console.error(e); }
}
function bukaModalTopPerformance() {
    document.getElementById('judulModalTopPerf').innerText = 'TOP PERFORMANCE - SEMUA KARYAWAN';
    const tableBody = document.getElementById('tableBodyTopPerf');
    tableBody.innerHTML = '';
    if (!masterTopPerfData || masterTopPerfData.length === 0) {
        tableBody.innerHTML = `<tr><td colspan="4" class="text-center text-muted py-4">Belum ada data untuk bulan ini.</td></tr>`;
    } else {
        let totalSubmisi = 0, totalNilai = 0;
        masterTopPerfData.forEach((item, index) => {
            totalSubmisi += item.submit;
            totalNilai += item.nilai;
            const medal = index === 0 ? '🥇' : index === 1 ? '🥈' : index === 2 ? '🥉' : '';
            tableBody.innerHTML += `
                <tr>
                    <td class="ps-4"><span class="badge ${index < 3 ? 'bg-danger' : 'bg-secondary'}">${index + 1}</span> ${medal}</td>
                    <td class="fw-semibold">${item.name}</td>
                    <td class="text-center">${item.submit}x submit</td>
                    <td class="text-center fw-bold text-danger">${item.nilai} Poin</td>
                </tr>`;
        });
        document.getElementById('perfTotalSubmit').innerText = masterTopPerfData.length;
        document.getElementById('perfTotalSubmisi').innerText = totalSubmisi;
        document.getElementById('perfTotalNilai').innerText = totalNilai;
    }
    new bootstrap.Modal(document.getElementById('modalTopPerformance')).show();
}

window.onload = loadData;
</script>
</body>
</html>