<?php 
// KONEKSI DATABASE DENGAN ERROR HANDLING
// Kredensial untuk hosting InfinityFree
$host = "sql100.byetcluster.com";
$user = "if0_40602786";
$password = "Portofolio3526";
$dbname = "if0_40602786_beauty";

// Cek apakah di localhost atau hosting
$is_localhost = in_array($_SERVER['HTTP_HOST'] ?? 'localhost', ['localhost', '127.0.0.1', 'localhost:8080']);

// Di hosting, biasanya:
// $host = "localhost"; 
// $user = "username_database_hosting";
// $password = "password_database_hosting";
// $dbname = "nama_database_hosting";

$conn = @mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    // Log error untuk debugging (jangan tampilkan ke user)
    @error_log("Database connection failed: " . mysqli_connect_error());
    
    // Tampilkan pesan umum tanpa detail error
    die("<!DOCTYPE html><html><head><title>Website Maintenance</title><style>body{font-family:Arial;text-align:center;padding:50px;background:#f5f5f5;}h1{color:#e91e63;}</style></head><body><h1>🔧 Website Sedang Maintenance</h1><p>Mohon maaf, website sedang dalam perbaikan.<br>Silakan coba lagi dalam beberapa saat.</p></body></html>");
}

// Set charset untuk mencegah masalah encoding
mysqli_set_charset($conn, "utf8mb4");

// FUNCTION UTAMA DENGAN ERROR HANDLING
function query($query) {
    global $conn;

    $result = @mysqli_query($conn, $query);

    if (!$result) {
        @error_log("Query Error: " . mysqli_error($conn) . " | Query: " . $query);
        // Jangan tampilkan error detail ke user di production
        return [];
    }

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

// FUNCTION FORMAT RUPIAH
function rupiah($angka){
    $hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
    return $hasil_rupiah;
}

// FUNCTION UNTUK DEBUG
function debug_data($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

?>
