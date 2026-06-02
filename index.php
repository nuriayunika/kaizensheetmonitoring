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

        body {
            background-color: var(--bg-body);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            margin: 0;
            display: flex;
            align-items: center; 
            justify-content: center; 
            height: 100vh;
            overflow: hidden;
        }

        .dashboard-container {
            width: 98%;
            max-width: 1500px; 
            padding: 20px;
            background: white;
            border-radius: 24px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            position: relative;
        }

        .upload-section {
            position: absolute;
            top: 20px;
            left: 30px;
            z-index: 100;
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
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid var(--bg-body);
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--primary-red);
            margin: 0;
        }

        .peak-mini-card {
            position: absolute;
            top: 20px;
            right: 30px;
            background: linear-gradient(135deg, var(--primary-red), var(--secondary-red));
            padding: 8px 18px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 15px;
            color: white;
        }

        .peak-mini-card .val { font-size: 1.4rem; font-weight: 800; color: var(--accent-gold); }

        .row-horizontal {
            display: flex;
            gap: 20px;
            align-items: stretch;
            width: 100%;
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
        }

        .card-radar-main { flex: 1.5; border-top: 4px solid var(--primary-red); }
        .card-radar-sub { flex: 1; }
        .card-employee-main { flex: 1.2; }

        .card-label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 18px; display: flex; align-items: center; gap: 8px; }
        .card-label::before { content: ''; width: 4px; height: 14px; background: var(--primary-red); border-radius: 2px; }
        .chart-box { flex: 1; position: relative; min-height: 0; }

        .table-custom thead { background-color: var(--bg-body); font-size: 0.7rem; }
        .table-custom td { font-size: 0.85rem; vertical-align: middle; }
    </style>
</head>
<body>

<div class="dashboard-container">
    <div class="upload-section">
        <button class="btn-upload" onclick="promptImport()">+ Import Data</button>
    </div>

    <div class="header">
        <h1>KAIZEN <span style="font-weight:300">ANALYTICS SYSTEM</span></h1>
        
        <div class="d-flex justify-content-center align-items-center gap-2 mt-2">
            <span style="font-size: 0.75rem; font-weight: bold; color: #64748b;">TAHUN:</span>
            <select id="filterTahun" class="form-select form-select-sm" style="width: 90px; border-radius: 8px;" onchange="loadData()">
                <option value="2024">2024</option>
                <option value="2025" selected>2025</option>
                <option value="2026">2026</option>
            </select>
        </div>

        <div class="d-flex justify-content-center mt-2">
            <div class="btn-group btn-group-sm flex-wrap shadow-sm" role="group" aria-label="Filter Bulan" style="border-radius: 8px; overflow: hidden;">
                <button type="button" class="btn btn-outline-danger px-3" onclick="pilihBulan(1, this)">Jan</button>
                <button type="button" class="btn btn-outline-danger px-3" onclick="pilihBulan(2, this)">Feb</button>
                <button type="button" class="btn btn-outline-danger px-3" onclick="pilihBulan(3, this)">Mar</button>
                <button type="button" class="btn btn-outline-danger px-3" onclick="pilihBulan(4, this)">Apr</button>
                <button type="button" class="btn btn-outline-danger px-3" onclick="pilihBulan(5, this)">Mei</button>
                <button type="button" class="btn btn-outline-danger px-3" onclick="pilihBulan(6, this)">Jun</button>
                <button type="button" class="btn btn-outline-danger px-3" onclick="pilihBulan(7, this)">Jul</button>
                <button type="button" class="btn btn-danger px-3 active" id="btnDefaultBulan" onclick="pilihBulan(8, this)">Agu</button>
                <button type="button" class="btn btn-outline-danger px-3" onclick="pilihBulan(9, this)">Sep</button>
                <button type="button" class="btn btn-outline-danger px-3" onclick="pilihBulan(10, this)">Okt</button>
                <button type="button" class="btn btn-outline-danger px-3" onclick="pilihBulan(11, this)">Nov</button>
                <button type="button" class="btn btn-outline-danger px-3" onclick="pilihBulan(12, this)">Des</button>
            </div>
        </div>
    </div>

    <div class="peak-mini-card">
        <div>
            <div style="font-size: 0.6rem; font-weight: 800; opacity: 0.8;">PEAK PERFORMANCE</div>
            <div id="topDeptName" style="font-size: 0.75rem; font-weight: 600;">LOADING...</div>
        </div>
        <div class="val" id="topScore">0%</div>
    </div>

    <div class="row-horizontal">
        <div class="k-card card-radar-main">
            <div class="card-label d-flex justify-content-between align-items-center">
                <span>Awareness Ratio</span>
                <span class="text-muted fw-normal" style="font-size: 0.65rem; text-transform: none; margin-top: 2px;">*Klik nama departemen untuk detail</span>
            </div>
            <div class="chart-box" id="container-dept"><canvas id="chartDeptRadar"></canvas></div>
        </div>

        <div class="k-card card-radar-sub">
            <div class="card-label">Category Tendency</div>
            <div class="chart-box" id="container-radar"><canvas id="chartRadar"></canvas></div>
        </div>

        <div class="k-card card-employee-main">
            <div class="card-label d-flex justify-content-between align-items-center">
                <span>Top Score Employee</span>
                <button class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size: 0.65rem;" onclick="resetModalKeSemuaKaryawan()">
                    Lihat Semua
                </button>
            </div>
            <div class="chart-box" id="container-emp"><canvas id="chartEmployee"></canvas></div>
        </div>
    </div>
</div>

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

    if (formValues) {
        uploadData(formValues.file);
    }
}

function uploadData(file) {
    let formData = new FormData();
    formData.append('file_excel', file);

    Swal.fire({ title: 'Mengunggah...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

    fetch('proses_import.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        Swal.fire({
            title: 'Hasil Import',
            html: result, // Menampilkan respon asli dari PHP
            icon: 'info'
        });
    })
    .catch(error => {
        Swal.fire('Error!', 'Gagal menghubungi server', 'error');
    });
}

window.activeCharts = {};
let masterEmployeeData = []; 
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
    loadData(); 
}

function resetCanvas(containerId, canvasId) {
    const container = document.getElementById(containerId);
    if (window.activeCharts[canvasId]) {
        window.activeCharts[canvasId].destroy();
    }
    container.innerHTML = `<canvas id="${canvasId}"></canvas>`;
    return document.getElementById(canvasId).getContext('2d');
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
            if(!data.karyawan || data.karyawan.length === 0) {
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

        // HITUNG ULANG PERSENTASE DI SINI (AGAR SAMA DENGAN POPUP)
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
                    data: processedDeptValues, // Menggunakan hasil hitung baru
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
                                // Tooltip sekarang menampilkan persentase dan jumlah (sinkron dengan popup)
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

        // ... (lanjutkan ke bagian radar chart kategori dan employee seperti kode lama Anda)

        const rawRadarValues = data.radar.values || []; 
        const cleanRadarValues = rawRadarValues.map(val => Math.round(val)); 
        const totalAktual = cleanRadarValues.reduce((a, b) => a + b, 0);
        const processedRadarValues = cleanRadarValues.map(val => totalAktual > 0 ? parseFloat(((val / totalAktual) * 100).toFixed(1)) : 0);
        // ... (setelah const processedRadarValues didefinisikan)

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
            // TAMBAHKAN BLOK TOOLTIP INI:
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let index = context.dataIndex;
                        let jumlahKategori = cleanRadarValues[index]; // Jumlah asli
                        let persentase = processedRadarValues[index]; // Persentase
                        
                        return `${context.label}: ${jumlahKategori} / ${totalAktual} (${persentase}%)`;
                    }
                }
            }
        }
    }
});

        if (data.employee && data.employee.labels) {
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
                plugins: [ChartDataLabels]
            });
            masterEmployeeData = data.employee.labels.map((name, index) => ({ name: name, val: data.employee.values[index] }));
        }

        if (processedDeptValues.length > 0) {
            const maxVal = Math.max(...processedDeptValues);
            const idx = processedDeptValues.indexOf(maxVal);
            document.getElementById('topScore').innerText = maxVal + '%';
            document.getElementById('topDeptName').innerText = data.dept.labels[idx];
        }
    } catch (e) { console.error(e); }
}
window.onload = loadData;
</script>
</body>
</html>