<?php 
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = $_ENV['DB_HOST'] ?? getenv("DB_HOST");
$user = $_ENV['DB_USER'] ?? getenv("DB_USER");
$password = $_ENV['DB_PASS'] ?? getenv("DB_PASS");
$dbname = $_ENV['DB_NAME'] ?? getenv("DB_NAME");
$port = $_ENV['DB_PORT'] ?? getenv("DB_PORT") ?: 3306;

$conn = mysqli_connect($host, $user, $password, $dbname, $port);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}


// FUNCTION UTAMA DENGAN ERROR HANDLING
function query($query) {
    global $conn;

    $result = mysqli_query($conn, $query);

    if (!$result) {
        error_log("Query Error: " . mysqli_error($conn) . " | Query: " . $query);
        die("Query Error: " . mysqli_error($conn));
    }

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

// FUNCTION TAMBAH USER DENGAN PREPARED STATEMENT
function add_user($data) {
    global $conn;
    
    $stmt = $conn->prepare("INSERT INTO tb_user (username, nama, alamat, email, password) VALUES (?, ?, ?, ?, ?)");
    
    $username = mysqli_real_escape_string($conn, $data["username"]);
    $nama = mysqli_real_escape_string($conn, $data["nama"]);
    $alamat = mysqli_real_escape_string($conn, $data["alamat"]);
    $email = mysqli_real_escape_string($conn, $data["email"]);
    $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
    
    $stmt->bind_param("sssss", $username, $nama, $alamat, $email, $password_hash);
    
    if ($stmt->execute()) {
        return $stmt->insert_id;
    } else {
        error_log("Error add_user: " . $stmt->error);
        return false;
    }
}

// FUNCTION TAMBAH ORDER DENGAN VALIDASI
function add_orders($data) {
    global $conn;

    $id_user = intval($data["id_user"]);
    $id_jasa = intval($data["jasa_kirim"]);
    $id_metode = intval($data["metode"]);
    $pesan = mysqli_real_escape_string($conn, $data["pesan"]);
    $konfirmasi = "Belum Terkonfirmasi";
    $status = "Belum Bayar";
    $tanggal_sekarang = date("Y-m-d H:i:s");
    
    $stmt = $conn->prepare("INSERT INTO orders (id_user, id_jasa, id_metode, tanggal, pesan, konfirmasi, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiissss", $id_user, $id_jasa, $id_metode, $tanggal_sekarang, $pesan, $konfirmasi, $status);
    
    if ($stmt->execute()) {
        return $stmt->insert_id;
    } else {
        error_log("Error add_orders: " . $stmt->error);
        return false;
    }
}

// FUNCTION TAMBAH DETAIL ORDER SINGLE
function add_details_now($data, $id_order) {
    global $conn;
    
    $id_barang = intval($data["id_barang"]);
    $qty = intval($data["qty"]);
    $harga_satuan = floatval($data["harga_satuan"]);
    
    $stmt = $conn->prepare("INSERT INTO details (id_orders, id_barang, harga_satuan, qty) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iidi", $id_order, $id_barang, $harga_satuan, $qty);
    
    return $stmt->execute();
}

// FUNCTION TAMBAH DETAIL ORDER MULTIPLE
function add_details($data, $id_order) {
    global $conn;
    
    $id_barang = isset($data["id_barang"]) ? $data["id_barang"] : array();
    $qty = isset($data["qty"]) ? $data["qty"] : array();
    $harga_satuan = isset($data["harga_satuan"]) ? $data["harga_satuan"] : array();
    
    $success_count = 0;
    
    foreach ($id_barang as $index => $barang_id) {
        $barang_id = intval($barang_id);
        $qty_barang = intval($qty[$barang_id]);
        $harga_satuan_barang = floatval($harga_satuan[$barang_id]);
        
        $stmt = $conn->prepare("INSERT INTO details (id_orders, id_barang, harga_satuan, qty) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iidi", $id_order, $barang_id, $harga_satuan_barang, $qty_barang);
        
        if ($stmt->execute()) {
            $success_count++;
        }
    }
    
    return $success_count;
}

// FUNCTION FORMAT RUPIAH
function rupiah($angka){
    $hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
    return $hasil_rupiah;
}

// FUNCTION TAMBAH KERANJANG DENGAN SESSION CHECK
function add_keranjang($data) {
    global $conn;
    
    // Cek session
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION["id_user"])) {
        return false;
    }
    
    $id = intval($data["id_barang"]);
    $user = intval($_SESSION["id_user"]);
    $qty = intval($data["qty"]);

    $stmt = $conn->prepare("INSERT INTO keranjang (id_barang, id_user, qty) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $id, $user, $qty);
    
    if ($stmt->execute()) {
        return true;
    }

    // If insert failed because a duplicate key exists, try to increase qty instead
    $errno = mysqli_errno($conn);
    if ($errno == 1062) { // duplicate entry
        $stmt2 = $conn->prepare("UPDATE keranjang SET qty = qty + ? WHERE id_barang = ? AND id_user = ?");
        $stmt2->bind_param('iii', $qty, $id, $user);
        return $stmt2->execute();
    }

    error_log("add_keranjang failed: (".mysqli_errno($conn).") ".mysqli_error($conn));
    return false;
}

// FUNCTION CEK KERANJANG
function cek_keranjang($id_barang, $id_user) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM keranjang WHERE id_barang = ? AND id_user = ?");
    $stmt->bind_param("ii", $id_barang, $id_user);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows;
}

// FUNCTION HAPUS BARANG DARI KERANJANG
function hapusBarang($id_user, $id_barang) {
    global $conn;
    
    $stmt = $conn->prepare("DELETE FROM keranjang WHERE id_user = ? AND id_barang = ?");
    $stmt->bind_param("ii", $id_user, $id_barang);
    return $stmt->execute();
}

// FUNCTION HAPUS MULTIPLE BARANG
function hapus_barang_dari_keranjang($id_barang) {
    global $conn;
    
    if (empty($id_barang)) return false;
    
    $placeholders = str_repeat('?,', count($id_barang) - 1) . '?';
    $stmt = $conn->prepare("DELETE FROM keranjang WHERE id_barang IN ($placeholders)");
    
    $types = str_repeat('i', count($id_barang));
    $stmt->bind_param($types, ...$id_barang);
    
    return $stmt->execute();
}

// FUNCTION PERHITUNGAN HARGA JUAL
function calculate_selling_price($harga_beli) {
    $harga_beli = floatval($harga_beli);
    
    if ($harga_beli <= 1000000) {
        $margin = 0.3; // 30%
    } elseif ($harga_beli <= 5000000) {
        $margin = 0.2; // 20%
    } elseif ($harga_beli <= 15000000) {
        $margin = 0.1; // 10%
    } elseif ($harga_beli <= 25000000) {
        $margin = 0.08; // 8%
    } else {
        $margin = 0.05; // 5%
    }
    
    return $harga_beli * (1 + $margin);
}

// FUNCTION TAMBAH PRODUK DENGAN FILE UPLOAD
// helper: process uploaded image (validate + resize) and store under assets/img/
function process_uploaded_image($file, $maxDim = 1200) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) return false;

    $tmp = $file['tmp_name'];
    $info = @getimagesize($tmp);
    if (!$info) return false;

    $mime = $info['mime'];
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/avif'];
    if (!in_array($mime, $allowed)) return false;

    $ext = '';
    switch ($mime) {
        case 'image/jpeg': $ext = '.jpg'; break;
        case 'image/png': $ext = '.png'; break;
        case 'image/gif': $ext = '.gif'; break;
        case 'image/webp': $ext = '.webp'; break;
        case 'image/avif': $ext = '.avif'; break;
        default: $ext = '.jpg';
    }

    $name = uniqid() . $ext;
    $dest = __DIR__ . '/../assets/img/' . $name;
    if (!is_dir(dirname($dest))) mkdir(dirname($dest), 0777, true);

    // if AVIF, or GD isn't available to process images, just move the uploaded file (best-effort)
    if ($mime === 'image/avif' || !function_exists('imagecreatefromstring')) {
        // If GD is not available we cannot resize/convert safely — fallback to move_uploaded_file
        // preserve extension from original filename if possible
        $origExt = pathinfo($file['name'], PATHINFO_EXTENSION);
        if ($origExt) {
            $dest = __DIR__ . '/../assets/img/' . uniqid() . '.' . strtolower($origExt);
            if (!is_dir(dirname($dest))) mkdir(dirname($dest), 0777, true);
            if (move_uploaded_file($tmp, $dest)) return 'assets/img/' . basename($dest);
        }
        // fallback to original plan
        if (move_uploaded_file($tmp, $dest)) return 'assets/img/' . $name;
        return false;
    }

    // try to create image resource and resize if needed
    $data = file_get_contents($tmp);
    if ($data === false) return false;

    $src = @imagecreatefromstring($data);
    if (!$src) {
        // fallback to simple move (as a last resort)
        if (move_uploaded_file($tmp, $dest)) return 'assets/img/' . $name;
        return false;
    }

    $width = imagesx($src);
    $height = imagesy($src);
    $scale = min(1, $maxDim / max($width, $height));
    $newW = (int) round($width * $scale);
    $newH = (int) round($height * $scale);

    if ($scale < 1) {
        $dst = imagecreatetruecolor($newW, $newH);
        // preserve transparency for png/webp/gif
        if (in_array($mime, ['image/png','image/webp','image/gif'])) {
            imagecolortransparent($dst, imagecolorallocatealpha($dst, 0, 0, 0, 127));
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        }
        imagecopyresampled($dst, $src, 0,0,0,0, $newW, $newH, $width, $height);
    } else {
        $dst = $src; // no resize
    }

    // save file according to mime
    $ok = false;
    switch ($mime) {
        case 'image/jpeg': $ok = imagejpeg($dst, $dest, 86); break;
        case 'image/png': $ok = imagepng($dst, $dest); break;
        case 'image/gif': $ok = imagegif($dst, $dest); break;
        case 'image/webp': $ok = imagewebp($dst, $dest); break;
        default: $ok = imagejpeg($dst, $dest, 86); break;
    }

    if (is_resource($src) && $src !== $dst) imagedestroy($src);
    if (is_resource($dst)) imagedestroy($dst);

    if ($ok) return 'assets/img/' . $name;
    return false;
}

function add_produk($data, $file) {
    global $conn;
    
    try {
        $nama = mysqli_real_escape_string($conn, $data["nama_barang"]);
        $kategori = mysqli_real_escape_string($conn, $data["kategori"]);
        $merk = mysqli_real_escape_string($conn, $data["merk"]);
        $stok = intval($data["stok"]);
        $keterangan = mysqli_real_escape_string($conn, $data["keterangan"]);
        $harga_beli = floatval($data['harga']);
        
        // Hitung harga jual
        $harga_jual = calculate_selling_price($harga_beli);
        
        // Handle file upload with validation + resize
        $nama_file_database = '';
        if (isset($file["img"]) && $file["img"]["error"] === UPLOAD_ERR_OK) {
            $processed = process_uploaded_image($file["img"], 1200);
            if ($processed) $nama_file_database = $processed;
        }
        
        $stmt = $conn->prepare("INSERT INTO tb_barang (nama_barang, harga, img, kategori, merk, keterangan, stok, terjual) VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
        $stmt->bind_param("sdssssi", $nama, $harga_jual, $nama_file_database, $kategori, $merk, $keterangan, $stok);
        
        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            throw new Exception("Gagal menyimpan produk: " . $stmt->error);
        }
        
    } catch (Exception $e) {
        error_log("Error add_produk: " . $e->getMessage());
        return false;
    }
}

// FUNCTION TAMBAH STOK
function add_stok($data) {
    global $conn;
    
    $id = intval($data["id_barang"]);
    $stok = intval($data["stok"]);

    $stmt = $conn->prepare("UPDATE tb_barang SET stok = stok + ? WHERE id_barang = ?");
    $stmt->bind_param("ii", $stok, $id);
    
    return $stmt->execute();
}

// FUNCTION SINGKAT NAMA BARANG
function singkat($nama_barang) {
    $max_length = 20;
    
    if (strlen($nama_barang) > $max_length) {
        $nama_pendek = substr($nama_barang, 0, $max_length);
        $posisi_spasi = strrpos($nama_pendek, ' ');
        
        if ($posisi_spasi !== false) {
            $nama_pendek = substr($nama_pendek, 0, $posisi_spasi);
        }
        
        return $nama_pendek . '...';
    }
    
    return $nama_barang;
}

// FUNCTION EDIT PRODUK
function edit_produk($data, $file = null) {
    global $conn;
    
    $id = intval($data["id_barang"]);
    $nama_barang = mysqli_real_escape_string($conn, $data["nama_barang"]);
    $kategori = mysqli_real_escape_string($conn, $data["kategori"]);
    $merk = mysqli_real_escape_string($conn, $data["merk"]);
    $harga = floatval($data["harga"]);
    $keterangan = mysqli_real_escape_string($conn, $data["keterangan"]);

    // If an image file is supplied and uploaded, save it and update the img column
    $img_db = null;
    if ($file && isset($file['img']) && $file['img']['error'] === UPLOAD_ERR_OK) {
        $processed = process_uploaded_image($file['img'], 1200);
        if ($processed) $img_db = $processed;
    }

    if ($img_db) {
        $stmt = $conn->prepare("UPDATE tb_barang SET nama_barang = ?, kategori = ?, merk = ?, harga = ?, keterangan = ?, img = ? WHERE id_barang = ?");
        $stmt->bind_param("sssdssi", $nama_barang, $kategori, $merk, $harga, $keterangan, $img_db, $id);
    } else {
        $stmt = $conn->prepare("UPDATE tb_barang SET nama_barang = ?, kategori = ?, merk = ?, harga = ?, keterangan = ? WHERE id_barang = ?");
        $stmt->bind_param("sssdsi", $nama_barang, $kategori, $merk, $harga, $keterangan, $id);
    }
    
    return $stmt->execute();
}

// FUNCTION HAPUS PRODUK
function hapus_produk($id_barang) {
    global $conn;
    
    $stmt = $conn->prepare("DELETE FROM tb_barang WHERE id_barang = ?");
    $stmt->bind_param("i", $id_barang);
    
    return $stmt->execute();
}

// FUNCTION UPLOAD BUKTI PEMBAYARAN
function upbukti($data, $file) {
    global $conn;
    
    $id_orders = intval($data["id_orders"]);
    $status = "Menunggu Konfirmasi";
    
    // Handle file upload
    $nama_file_database = '';
    if (isset($file["bukti"]) && $file["bukti"]["error"] === UPLOAD_ERR_OK) {
        $nama_file = uniqid() . '_' . basename($file["bukti"]["name"]);
        $tujuan = __DIR__ . '/../upload/' . $nama_file;
        
        // Create directory if not exists
        if (!is_dir(dirname($tujuan))) {
            mkdir(dirname($tujuan), 0777, true);
        }
        
        if (move_uploaded_file($file["bukti"]["tmp_name"], $tujuan)) {
            $nama_file_database = 'upload/' . $nama_file;
        }
    }
    
    $stmt = $conn->prepare("UPDATE orders SET bukti_pembayaran = ?, status = ? WHERE id_orders = ?");
    $stmt->bind_param("ssi", $nama_file_database, $status, $id_orders);
    
    return $stmt->execute();
}

// FUNCTION KONFIRMASI ORDER
function confirm($id_orders) {
    global $conn;
    
    $konfirmasi = "Terkonfirmasi";
    $status = "Diproses";
    
    $stmt = $conn->prepare("UPDATE orders SET konfirmasi = ?, status = ? WHERE id_orders = ?");
    $stmt->bind_param("ssi", $konfirmasi, $status, $id_orders);
    
    return $stmt->execute();
}

// FUNCTION KIRIM ORDER
function kirim($id_orders, $resi = null) {
    global $conn;

    // Allow kirim() to accept either an integer id or an array (POST data)
    if (is_array($id_orders)) {
        $data = $id_orders;
        $id_orders = isset($data['id_orders']) ? intval($data['id_orders']) : 0;
        $resi = isset($data['resi']) ? trim($data['resi']) : $resi;
    }

    $id_orders = intval($id_orders);
    if ($id_orders <= 0) return false;

    // ensure `resi` column exists in orders table; add if missing
    $colCheck = mysqli_query($conn, "SHOW COLUMNS FROM orders LIKE 'resi'");
    if (!$colCheck || mysqli_num_rows($colCheck) === 0) {
        // Add the resi column if not present (best-effort)
        @mysqli_query($conn, "ALTER TABLE orders ADD COLUMN resi VARCHAR(255) DEFAULT NULL");
    }

    $status = "Dikirim";

    if ($resi === null || $resi === '') {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id_orders = ?");
        $stmt->bind_param("si", $status, $id_orders);
    } else {
        $stmt = $conn->prepare("UPDATE orders SET status = ?, resi = ? WHERE id_orders = ?");
        $stmt->bind_param("ssi", $status, $resi, $id_orders);
    }

    return $stmt->execute();
}

// FUNCTION TAMBAH RATING
function add_rating($id_orders, $id_barang, $rating, $ulasan, $foto = '') {
    global $conn;
    
    $rating = intval($rating);
    $ulasan = mysqli_real_escape_string($conn, $ulasan);
    $foto = mysqli_real_escape_string($conn, $foto);
    
    $stmt = $conn->prepare("UPDATE details SET rating = ?, ulasan = ?, foto = ? WHERE id_orders = ? AND id_barang = ?");
    $stmt->bind_param("issii", $rating, $ulasan, $foto, $id_orders, $id_barang);
    
    return $stmt->execute();
}

// FUNCTION SELESAI ORDER
function selesai($id_orders) {
    global $conn;
    
    $status = "Selesai";
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id_orders = ?");
    $stmt->bind_param("si", $status, $id_orders);
    
    return $stmt->execute();
}

// FUNCTION PENGELUARAN STOK
function pengeluaranstok($data) {
    global $conn;
    
    $id_barang = intval($data['id_barang']);
    $harga_jual = floatval($data['harga']);
    $qty = intval($data['stok']);
    $tgl = date("Y-m-d H:i:s");

    // Hitung harga beli berdasarkan margin
    if ($harga_jual <= 1000000) {
        $margin = 0.3;
    } elseif ($harga_jual <= 5000000) {
        $margin = 0.2;
    } elseif ($harga_jual <= 15000000) {
        $margin = 0.1;
    } elseif ($harga_jual <= 25000000) {
        $margin = 0.08;
    } else {
        $margin = 0.05;
    }
    
    $harga_beli = $harga_jual / (1 + $margin); // Hitung harga beli dari harga jual

    // total cost
    $total = $harga_beli * $qty;

    // Try to get product name for record
    $nama = '';
    $stmtName = $conn->prepare("SELECT nama_barang FROM tb_barang WHERE id_barang = ? LIMIT 1");
    $stmtName->bind_param('i', $id_barang);
    $stmtName->execute();
    $rowName = $stmtName->get_result()->fetch_assoc();
    if ($rowName) $nama = $rowName['nama_barang'];

    $stmt = $conn->prepare("INSERT INTO tb_pengeluaran (tanggal, nama_pengeluaran, harga, qty, total) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssidd", $tgl, $nama, $harga_beli, $qty, $total);
    
    return $stmt->execute();
}

// FUNCTION TAMBAH PENGELUARAN
function add_pengeluaran($data, $id_barang) {
    global $conn;
    
    $harga_beli = floatval($data['harga']);
    $qty = intval($data['stok']);
    $tgl = date("Y-m-d H:i:s");

    // compute total and get product name
    $total = $harga_beli * $qty;
    $nama = '';
    $stmtName = $conn->prepare("SELECT nama_barang FROM tb_barang WHERE id_barang = ? LIMIT 1");
    $stmtName->bind_param('i', $id_barang);
    $stmtName->execute();
    $rowName = $stmtName->get_result()->fetch_assoc();
    if ($rowName) $nama = $rowName['nama_barang'];

    $stmt = $conn->prepare("INSERT INTO tb_pengeluaran (tanggal, nama_pengeluaran, harga, qty, total) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssidd", $tgl, $nama, $harga_beli, $qty, $total);
    
    return $stmt->execute();
}

// FUNCTION AJUKAN RETUR
function add_retur($data, $file) {
    global $conn;
    
    $id_orders = intval($data["id_orders"]);
    $tgl = date("Y-m-d H:i:s");
    $alasan = mysqli_real_escape_string($conn, $data["alasan"]);
    $status = "Menunggu";

    // Handle file upload
    $nama_file_database = '';
    if (isset($file["bukti"]) && $file["bukti"]["error"] === UPLOAD_ERR_OK) {
        $nama_file = uniqid() . '_' . basename($file["bukti"]["name"]);
        $tujuan = __DIR__ . '/../upload/' . $nama_file;
        
        if (!is_dir(dirname($tujuan))) {
            mkdir(dirname($tujuan), 0777, true);
        }
        
        if (move_uploaded_file($file["bukti"]["tmp_name"], $tujuan)) {
            $nama_file_database = 'upload/' . $nama_file;
        }
    }

    $stmt = $conn->prepare("INSERT INTO tb_refund (id_orders, tanggal, alasan, bukti, status_refund) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $id_orders, $tgl, $alasan, $nama_file_database, $status);
    
    return $stmt->execute();
}

// FUNCTION UPDATE STATUS RETUR
function retur($id_orders) {
    global $conn;
    
    $status = "Diajukan Retur";
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id_orders = ?");
    $stmt->bind_param("si", $status, $id_orders);
    
    return $stmt->execute();
}

// FUNCTION UPDATE DATA PENERIMA
function penerima($data, $id_user){
    global $conn;
    
    $nama = mysqli_real_escape_string($conn, $data["nama"]);
    $telp = mysqli_real_escape_string($conn, $data["telp"]);
    $alamat = mysqli_real_escape_string($conn, $data["alamat"]);

    $stmt = $conn->prepare("UPDATE tb_user SET nama = ?, telp = ?, alamat = ? WHERE id_user = ?");
    $stmt->bind_param("sssi", $nama, $telp, $alamat, $id_user);
    
    return $stmt->execute();
}

// FUNCTION BATAL ORDER
function batal($id_orders) {
    global $conn;
    
    $status = "Dibatalkan";
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id_orders = ?");
    $stmt->bind_param("si", $status, $id_orders);
    
    return $stmt->execute();
}

// FUNCTION KONFIRMASI RETUR
function confirm_retur($id_orders) {
    global $conn;
    
    $status = "Retur";
    $status_refund = "Diterima";

    $conn->begin_transaction();
    
    try {
        // Update orders
        $stmt1 = $conn->prepare("UPDATE orders SET status = ? WHERE id_orders = ?");
        $stmt1->bind_param("si", $status, $id_orders);
        $stmt1->execute();
        
        // Update refund
        $stmt2 = $conn->prepare("UPDATE tb_refund SET status_refund = ? WHERE id_orders = ?");
        $stmt2->bind_param("si", $status_refund, $id_orders);
        $stmt2->execute();
        
        $conn->commit();
        return true;
        
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error confirm_retur: " . $e->getMessage());
        return false;
    }
}

// FUNCTION TOLAK RETUR
function tolak_retur($id_orders) {
    global $conn;
    
    $status = "Retur Ditolak";
    $status_refund = "Ditolak";

    $conn->begin_transaction();
    
    try {
        // Update orders
        $stmt1 = $conn->prepare("UPDATE orders SET status = ? WHERE id_orders = ?");
        $stmt1->bind_param("si", $status, $id_orders);
        $stmt1->execute();
        
        // Update refund
        $stmt2 = $conn->prepare("UPDATE tb_refund SET status_refund = ? WHERE id_orders = ?");
        $stmt2->bind_param("si", $status_refund, $id_orders);
        $stmt2->execute();
        
        $conn->commit();
        return true;
        
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error tolak_retur: " . $e->getMessage());
        return false;
    }
}

// FUNCTION UNTUK VALIDASI INPUT
function validate_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// FUNCTION UNTUK DEBUG
function debug_data($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

?>