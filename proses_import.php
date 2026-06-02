<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Fungsi ditaruh di luar loop agar tidak error
function getKatID($conn, $nama) {
    if (empty($nama)) return "NULL";
    // Mencegah error jika nama kategori mengandung tanda petik
    $nama = mysqli_real_escape_string($conn, $nama);
    $q = mysqli_query($conn, "SELECT id FROM categories WHERE nama_category = '$nama' LIMIT 1");
    $d = mysqli_fetch_assoc($q);
    return $d ? "'".$d['id']."'" : "NULL";
}

if (!isset($_FILES['file_excel'])) {
    die("Error: Tidak ada file yang dikirim.");
}

$file = $_FILES['file_excel']['tmp_name'];
if (empty($file)) {
    die("Error: File tidak terdeteksi!");
}

try {
    $spreadsheet = IOFactory::load($file);
    $sheetData = $spreadsheet->getActiveSheet()->toArray();

    $sukses = 0; $gagal = 0;

    // Mulai loop dari baris ke-1 (melewati header)
    for ($i = 1; $i < count($sheetData); $i++) {
        // Kita berikan nilai default '' (string kosong) jika data di excel null
        $nik        = trim((string)($sheetData[$i][0] ?? ''));
        $kategori1  = trim((string)($sheetData[$i][1] ?? ''));
        $kategori2  = trim((string)($sheetData[$i][2] ?? ''));
        $kategori3  = trim((string)($sheetData[$i][3] ?? ''));
        $kategori4  = trim((string)($sheetData[$i][4] ?? ''));
        $kategori5  = trim((string)($sheetData[$i][5] ?? ''));
        
        // Penyesuaian format tanggal
        $raw_date = $sheetData[$i][6] ?? '';
        if (is_numeric($raw_date)) {
            $tanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($raw_date)->format('Y-m-d');
        } else {
            $tanggal = date('Y-m-d', strtotime(str_replace('/', '-', $raw_date)));
        }

        $judul = mysqli_real_escape_string($conn, (string)($sheetData[$i][7] ?? ''));
        $score = (int)($sheetData[$i][8] ?? 0);

        if (empty($nik)) continue;

        $q_emp = mysqli_query($conn, "SELECT id FROM employees WHERE nik = '$nik' LIMIT 1");
        if (mysqli_num_rows($q_emp) > 0) {
            $emp_id = mysqli_fetch_assoc($q_emp)['id'];

            $c1 = getKatID($conn, $kategori1);
            $c2 = getKatID($conn, $kategori2);
            $c3 = getKatID($conn, $kategori3);
            $c4 = getKatID($conn, $kategori4);
            $c5 = getKatID($conn, $kategori5);

            $query_insert = "INSERT INTO kaizen_submissions 
                (employee_id, category_id_1, category_id_2, category_id_3, category_id_4, category_id_5, tanggal_input, judul_kaizen, total_score) 
                VALUES ('$emp_id', $c1, $c2, $c3, $c4, $c5, '$tanggal', '$judul', $score)";
            
            if (mysqli_query($conn, $query_insert)) {
                $sukses++;
            } else {
                echo "Gagal input baris " . ($i+1) . ": " . mysqli_error($conn) . "<br>";
                $gagal++;
            }
        } else {
            echo "Error: NIK $nik (baris " . ($i+1) . ") tidak terdaftar!<br>";
            $gagal++;
        }
    }
    
    echo "<br><b>Import Selesai!</b><br>Sukses: $sukses, Gagal: $gagal.";

} catch (Exception $e) {
    echo "Error Import: " . $e->getMessage();
}
?>