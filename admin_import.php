<?php
session_start();

// PASSWORD SEDERHANA (Ubah sesuai keinginanmu)
$password_admin = "kaizen2026"; 

if (isset($_POST['login'])) {
    if ($_POST['password'] == $password_admin) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $error = "Password Salah!";
    }
}

// JIKA BELUM LOGIN, TAMPILKAN FORM LOGIN
if (!isset($_SESSION['admin_logged_in'])) {
    echo "<form method='post' style='text-align:center; margin-top:100px;'>
            <h2>Admin Login</h2>
            <input type='password' name='password' placeholder='Masukkan Password' required>
            <button type='submit' name='login'>Masuk</button>
          </form>";
    exit;
}

// JIKA SUDAH LOGIN, TAMPILKAN FORM IMPORT
?>
<h2>Import Data Kaizen</h2>
<form action="proses_import.php" method="post" enctype="multipart/form-data">
    <input type="file" name="file_excel" required>
    <button type="submit" name="import">Upload & Import</button>
</form>