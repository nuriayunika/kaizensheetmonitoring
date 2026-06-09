<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
include 'koneksi.php';

// 1. TANGKAP PARAMETER
$bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('m');
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');

$response = [
    'radar' => ['labels' => [], 'values' => []],
    'dept' => ['labels' => [], 'values' => [], 'karyawan_ikut' => [], 'total_karyawan' => []],
    'employee' => ['labels' => [], 'values' => []],
    'kaizen_list' => [] 
];

// 2. DATA RADAR
$query_radar = "SELECT 
    SUM(CASE WHEN category_id_1 = 1 OR category_id_2 = 1 OR category_id_3 = 1 OR category_id_4 = 1 OR category_id_5 = 1 THEN 1 ELSE 0 END) as prod,
    SUM(CASE WHEN category_id_1 = 2 OR category_id_2 = 2 OR category_id_3 = 2 OR category_id_4 = 2 OR category_id_5 = 2 THEN 1 ELSE 0 END) as cost,
    SUM(CASE WHEN category_id_1 = 3 OR category_id_2 = 3 OR category_id_3 = 3 OR category_id_4 = 3 OR category_id_5 = 3 THEN 1 ELSE 0 END) as qual,
    SUM(CASE WHEN category_id_1 = 4 OR category_id_2 = 4 OR category_id_3 = 4 OR category_id_4 = 4 OR category_id_5 = 4 THEN 1 ELSE 0 END) as safe,
    SUM(CASE WHEN category_id_1 = 5 OR category_id_2 = 5 OR category_id_3 = 5 OR category_id_4 = 5 OR category_id_5 = 5 THEN 1 ELSE 0 END) as s3s3
    FROM kaizen_submissions 
    WHERE MONTH(tanggal_input) = '$bulan' AND YEAR(tanggal_input) = '$tahun'";

$run_radar = mysqli_query($conn, $query_radar);
if ($run_radar) {
    $row = mysqli_fetch_assoc($run_radar);
    $response['radar']['labels'] = ['PRODUCTIVITY', 'COST DOWN', 'QUALITY', 'SAFETY', '3S3T'];
    $response['radar']['values'] = [(int)$row['prod'], (int)$row['cost'], (int)$row['qual'], (int)$row['safe'], (int)$row['s3s3']];
}

// 3. DATA DEPT
$query_dept = mysqli_query($conn, "
    SELECT d.id, d.nama_dept, 
    (SELECT COUNT(*) FROM employees e WHERE e.dept_id = d.id) as total_karyawan,
    COUNT(DISTINCT ks.employee_id) as karyawan_ikut
    FROM departments d
    LEFT JOIN kaizen_submissions ks ON d.id = (SELECT dept_id FROM employees WHERE id = ks.employee_id)
        AND MONTH(ks.tanggal_input) = '$bulan' AND YEAR(ks.tanggal_input) = '$tahun'
    GROUP BY d.id, d.nama_dept ORDER BY d.nama_dept ASC");

while($row = mysqli_fetch_assoc($query_dept)) {
    $total = (int)$row['total_karyawan'];
    $ikut = (int)$row['karyawan_ikut'];
    $pembagi = ($total > 0) ? $total : 1;
    $persen = round((($ikut / $pembagi) * 100), 1);
    $response['dept']['labels'][] = $row['nama_dept'];
    $response['dept']['values'][] = ($persen > 100) ? 100 : $persen;
    $response['dept']['karyawan_ikut'][] = $ikut;
    $response['dept']['total_karyawan'][] = $total;
}

// 4. DATA LEADERBOARD
$query_emp = mysqli_query($conn, "SELECT e.nama_karyawan, MAX(ks.total_score) as skor_tertinggi 
                  FROM employees e JOIN kaizen_submissions ks ON e.id = ks.employee_id 
                  WHERE MONTH(ks.tanggal_input) = '$bulan' AND YEAR(ks.tanggal_input) = '$tahun'
                  GROUP BY e.id ORDER BY skor_tertinggi DESC"); 

while($row = mysqli_fetch_assoc($query_emp)) {
    $response['employee']['labels'][] = $row['nama_karyawan'];
    $response['employee']['values'][] = (int)$row['skor_tertinggi'];
}

// 5. DATA DETAIL KAIZEN
$query_detail = mysqli_query($conn, "
    SELECT ks.judul_kaizen, c.nama_category, e.nama_karyawan, d.nama_dept, ks.total_score
    FROM kaizen_submissions ks
    LEFT JOIN categories c ON ks.category_id_1 = c.id
    JOIN employees e ON ks.employee_id = e.id
    JOIN departments d ON e.dept_id = d.id
    WHERE MONTH(ks.tanggal_input) = '$bulan' AND YEAR(ks.tanggal_input) = '$tahun'
    ORDER BY ks.total_score DESC");

while($row = mysqli_fetch_assoc($query_detail)) {
    $response['kaizen_list'][] = $row;
}

header('Content-Type: application/json');
echo json_encode($response);
exit;
?>