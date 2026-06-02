<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include 'koneksi.php';

// Ambil input dari URL
$dept_name     = isset($_GET['dept']) ? trim($_GET['dept']) : '';
$bulan_pilihan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('m');
$tahun_pilihan = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');

if (empty($dept_name)) {
    echo json_encode([
        'rekap' => ['total_karyawan' => 0, 'karyawan_ikut' => 0, 'persentase' => 0],
        'karyawan' => []
    ]);
    exit;
}

try {
    // 1. MENGHITUNG TOTAL KARYAWAN OTOMATIS DARI DATABASE
    // Query ini menghitung jumlah total karyawan yang terdaftar di departemen tersebut
    $query_count = "SELECT COUNT(e.id) as total 
                    FROM employees e 
                    JOIN departments d ON e.dept_id = d.id 
                    WHERE UPPER(d.nama_dept) = UPPER(?)";
    
    $stmt_count = mysqli_prepare($conn, $query_count);
    mysqli_stmt_bind_param($stmt_count, "s", $dept_name);
    mysqli_stmt_execute($stmt_count);
    $res_count = mysqli_stmt_get_result($stmt_count);
    $row_count = mysqli_fetch_assoc($res_count);
    
    // Jika tidak ditemukan, minimal set 1 agar tidak error pembagian
    $total_karyawan_dept = ($row_count['total'] > 0) ? (int)$row_count['total'] : 1;

    // 2. MENGAMBIL DATA KARYAWAN YANG SUBMIT KAIZEN
    $query = "SELECT 
                e.nama_karyawan, 
                COUNT(ks.id) as total_kaizen
              FROM employees e
              JOIN departments d ON d.id = e.dept_id
              LEFT JOIN kaizen_submissions ks ON e.id = ks.employee_id
                 AND MONTH(ks.tanggal_input) = ? 
                 AND YEAR(ks.tanggal_input) = ?
              WHERE UPPER(d.nama_dept) = UPPER(?)
              GROUP BY e.id
              ORDER BY total_kaizen DESC";

    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iis", $bulan_pilihan, $tahun_pilihan, $dept_name);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $listKaryawan = [];
        $karyawan_aktif_ikut = 0;
        
        while ($row = mysqli_fetch_assoc($result)) {
            $total_input = (int)$row['total_kaizen'];
            
            // Masukkan ke daftar jika ada data kaizen
            if ($total_input > 0) {
                $karyawan_aktif_ikut++;
                $listKaryawan[] = [
                    'nama_karyawan' => $row['nama_karyawan'],
                    'total_kaizen'  => $total_input
                ];
            }
        }
        
        // Hitung persentase
        $persen_partisipasi = round(($karyawan_aktif_ikut / $total_karyawan_dept) * 100, 1);
        if ($persen_partisipasi > 100) $persen_partisipasi = 100;

        echo json_encode([
            'rekap' => [
                'total_karyawan' => $total_karyawan_dept,
                'karyawan_ikut'  => $karyawan_aktif_ikut,
                'persentase'     => $persen_partisipasi
            ],
            'karyawan' => $listKaryawan
        ]);
        
        mysqli_stmt_close($stmt);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => true, "message" => $e->getMessage()]);
}
?>