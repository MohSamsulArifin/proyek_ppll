<?php
echo "<h2>🛠️ DEBUG DETAILED KONEKSI</h2>";

// Test 1: Cek XAMPP Apache
echo "1. Testing Apache...<br>";
echo "PHP Version: " . phpversion() . "<br>";
echo "✅ Apache BERJALAN<br><br>";

// Test 2: Cek MySQL Service
echo "2. Testing MySQL Service...<br>";
$mysql_test = @mysqli_connect("localhost:3306", "root", "");
if ($mysql_test) {
    echo "✅ MySQL SERVICE BERJALAN<br>";
    
    // Test 2a: Cek databases available
    $databases = mysqli_query($mysql_test, "SHOW DATABASES");
    echo "Databases tersedia:<br>";
    $db_found = false;
    while ($db = mysqli_fetch_array($databases)) {
        echo "&nbsp;&nbsp;- " . $db[0] . "<br>";
        if ($db[0] == "beauty") $db_found = true;
    }
    
    if (!$db_found) {
        echo "❌ Database 'beauty' TIDAK DITEMUKAN<br>";
    }
    
} else {
    echo "❌ MySQL SERVICE GAGAL - Error: " . mysqli_connect_error() . "<br>";
}
echo "<br>";

// Test 3: Test koneksi dengan konfigurasi Anda
echo "3. Testing dengan konfigurasi Anda...<br>";
$host = "localhost:3306";
$user = "root";
$password = "";
$dbname = "beauty";

$conn = @mysqli_connect($host, $user, $password, $dbname);

if ($conn) {
    echo "✅ KONEKSI KE DATABASE 'beauty' BERHASIL<br>";
    
    // Test tables
    $tables = mysqli_query($conn, "SHOW TABLES");
    if (mysqli_num_rows($tables) > 0) {
        echo "Tabel yang ditemukan:<br>";
        while ($table = mysqli_fetch_array($tables)) {
            echo "&nbsp;&nbsp;- " . $table[0] . "<br>";
        }
    } else {
        echo "❌ Tidak ada tabel di database 'beauty'<br>";
    }
    
} else {
    echo "❌ KONEKSI KE DATABASE 'beauty' GAGAL<br>";
    echo "Error: " . mysqli_connect_error() . "<br>";
    
    // Coba alternatif
    echo "<br>Mencoba alternatif...<br>";
    
    // Coba tanpa port
    echo "a. Tanpa port... ";
    $conn2 = @mysqli_connect("localhost", "root", "", "beauty");
    echo $conn2 ? "✅ BERHASIL" : "❌ GAGAL";
    echo "<br>";
    
    // Coba tanpa database dulu
    echo "b. Koneksi MySQL saja... ";
    $conn3 = @mysqli_connect("localhost", "root", "");
    echo $conn3 ? "✅ BERHASIL" : "❌ GAGAL";
    echo "<br>";
}
?>