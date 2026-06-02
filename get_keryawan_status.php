<?php
include 'koneksi.php';

if (isset($_POST['dept_id'])) {
    $dept_id = mysqli_real_escape_string($conn, $_POST['dept_id']);

    // Query mencari semua karyawan di departemen tersebut + hitung submit Kaizen-nya
    $query = "SELECT 
                e.nik, 
                e.nama_karyawan,
                COUNT(s.id) AS jumlah_submit
              FROM employees e
              LEFT JOIN kaizen_submissions s ON s.employee_id = e.id
              WHERE e.dept_id = '$dept_id'
              GROUP BY e.id
              ORDER BY jumlah_submit DESC, e.nama_karyawan ASC";

    $result = mysqli_query($conn, $query);

    echo "<div class='row'>";
    
    // KOTAK KIRI: KARYAWAN AKTIF (Sudah Pernah Sumbang Kaizen)
    echo "<div class='col-md-6' style='margin-bottom: 15px;'>";
    echo "<h6 class='text-success' style='font-weight: bold; border-bottom: 2px solid #28a745; padding-bottom: 5px;'>✔ Aktif Kontribusi</h6>";
    echo "<ul class='list-group' style='max-height: 300px; overflow-y: auto;'>";
    
    $aktif_kosong = true;
    $tidak_aktif_list = []; 

    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['jumlah_submit'] > 0) {
            $aktif_kosong = false;
            echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
            echo "<div><small class='text-muted'>".$row['nik']."</small><br><strong>".$row['nama_karyawan']."</strong></div>";
            echo "<span class='badge bg-success rounded-pill'>".$row['jumlah_submit']." Kaizen</span>";
            echo "</li>";
        } else {
            $tidak_aktif_list[] = $row;
        }
    }
    if ($aktif_kosong) {
        echo "<li class='list-group-item text-muted text-center'>Belum ada karyawan aktif</li>";
    }
    echo "</ul>";
    echo "</div>";

    // KOTAK KANAN: KARYAWAN TIDAK AKTIF (Belum Pernah Sumbang Kaizen)
    echo "<div class='col-md-6'>";
    echo "<h6 class='text-danger' style='font-weight: bold; border-bottom: 2px solid #dc3545; padding-bottom: 5px;'>❌ Belum Kontribusi</h6>";
    echo "<ul class='list-group' style='max-height: 300px; overflow-y: auto;'>";
    
    if (empty($tidak_aktif_list)) {
        echo "<li class='list-group-item text-success text-center fw-bold'>Semua karyawan sudah aktif! ⭐</li>";
    } else {
        foreach ($tidak_aktif_list as $row) {
            echo "<li class='list-group-item'>";
            echo "<small class='text-muted'>".$row['nik']."</small><br>".$row['nama_karyawan'];
            echo "</li>";
        }
    }
    echo "</ul>";
    echo "</div>";
    
    echo "</div>";
}
?>