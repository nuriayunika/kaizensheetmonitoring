<?php
include 'koneksi.php';
$dept = $_GET['dept'];

$query = mysqli_query($conn, "SELECT e.section, COUNT(ks.id) as total 
          FROM employees e
          JOIN departments d ON e.dept_id = d.id
          LEFT JOIN kaizen_submissions ks ON e.id = ks.employee_id
          WHERE d.nama_dept = '$dept'
          GROUP BY e.section
          ORDER BY total DESC");

$data = [];
while($row = mysqli_fetch_assoc($query)) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
?>