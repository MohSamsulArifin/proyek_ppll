<?php 
include "../db/koneksi.php";
session_start();

if (isset($_SESSION['id_admin']) && isset($_SESSION['username'])) {
    $id_user = $_SESSION['id_admin'];
    $username = $_SESSION["username"];
    $nama = query("SELECT nama FROM tb_user WHERE id_user = '$id_user'")[0];
} else {
    echo "<script>document.location.href = 'login_admin.php';</script>";
}

$barang = query("SELECT * FROM tb_barang");

// Pemasukan per hari
$data_pemasukan = query("SELECT DATE_FORMAT(orders.tanggal, '%Y-%m-%d') AS tanggal, 
                                SUM(details.harga_satuan * details.qty) AS pemasukan 
                         FROM orders
                         INNER JOIN details USING(id_orders)
                         WHERE status = 'Selesai'
                         GROUP BY tanggal");

// Pengeluaran per hari
$data_pengeluaran = query("SELECT DATE_FORMAT(tanggal, '%Y-%m-%d') AS tanggal, 
                                  SUM(harga * qty) AS pengeluaran 
                           FROM tb_pengeluaran 
                           GROUP BY tanggal");

$data_pemasukan_json = json_encode($data_pemasukan);
$data_pengeluaran_json = json_encode($data_pengeluaran);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <title>Dashboard Beauty Store</title>

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- ===============================================
     🎀 PINK THEME BEAUTY STORE — ONLY COLOR CHANGED
     =============================================== -->
<style>
:root {
    --pink-primary: #e8a0b8ff;
    --pink-light: #ffebf2;
    --pink-bg: #ffe4ec;
    --pink-dark: #d88facff;
}

/* Background utama */
body.sb-nav-fixed {
    background-color: var(--pink-light) !important;
}

/* Navbar atas */
.sb-topnav {
    background-color: var(--pink-primary) !important;
}
.sb-topnav .navbar-brand {
    color: white !important;
    font-weight: bold;
}
#sidebarToggle {
    color: white !important;
}

/* Sidebar */
.sb-sidenav {
    background-color: var(--pink-primary) !important;
}
.sb-sidenav .nav-link, 
.sb-sidenav .sb-sidenav-menu-heading {
    color: white !important;
}
.sb-sidenav .nav-link:hover {
    background-color: var(--pink-dark) !important;
    color: white !important;
}
.sb-sidenav .nav-link.active {
    background-color: var(--pink-dark) !important;
}

/* Card */
.card {
    border-radius: 10px;
    border: none;
    background: white;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}
.card-header {
    background-color: var(--pink-primary);
    color: white !important;
    border-radius: 10px 10px 0 0 !important;
}

/* Title dan breadcrumb */
h1, h2 {
    color: var(--pink-primary) !important;
}
.breadcrumb-item.active {
    color: var(--pink-primary) !important;
    font-weight: bold;
}

/* Footer */
footer.bg-light {
    background-color: var(--pink-bg) !important;
}
footer .text-muted {
    color: var(--pink-dark) !important;
}

/* Chart background */
.chartjs-render-monitor {
    background: white !important;
    border-radius: 10px;
}
</style>

</head>
<body class="sb-nav-fixed">

    <nav class="sb-topnav navbar navbar-expand navbar-dark">
        <a class="navbar-brand ps-3" href="index.php">Admin Beauty</a>
        <button class="btn btn-link btn-sm ms-3" id="sidebarToggle"><i class="fas fa-bars"></i></button>

        <ul class="navbar-nav ms-auto me-3">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                   data-bs-toggle="dropdown"><?= $username ?></a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>


    <div id="layoutSidenav">
        <!-- Sidebar -->
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark">
                <div class="sb-sidenav-menu">
                    <div class="nav">

                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link active" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>

                        <div class="sb-sidenav-menu-heading">Interface</div>

                        <!-- Pesanan -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pesanan">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Pesanan
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="pesanan">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="batal.php">Pesanan Dibatalkan</a>
                                <a class="nav-link" href="konfirmasi.php">Konfirmasi Pesanan</a>
                                <a class="nav-link" href="diproses.php">Untuk Dikirim</a>
                                <a class="nav-link" href="selesai.php">Pesanan Selesai</a>
                                <a class="nav-link" href="refund.php">Pengajuan Refund</a>
                            </nav>
                        </div>

                        <!-- Produk -->
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#produk">
                            <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                            Manajemen Produk
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="produk">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="all_product.php">Lihat Semua Produk</a>
                                <a class="nav-link" href="edit_product.php">Edit & Hapus Produk</a>
                                <a class="nav-link" href="add_product.php">Tambah Produk & Stok</a>
                            </nav>
                        </div>

                        <div class="sb-sidenav-menu-heading">Addons</div>
                        <a class="nav-link" href="pendapatan.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-money-bill"></i></div>
                            Pemasukan
                        </a>
                        <a class="nav-link" href="pengeluaran.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-wallet"></i></div>
                            Pengeluaran
                        </a>
                        <a class="nav-link" href="user.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                            Pelanggan
                        </a>
                    </div>
                </div>
            </nav>
        </div>


        <!-- Content -->
        <div id="layoutSidenav_content">
            <main class="px-4">
                <h1 class="mt-4">Dashboard</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>

                <div class="row">
                    <!-- Chart Pemasukan -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header"><h2>Pemasukan</h2></div>
                            <div class="card-body">
                                <canvas id="chartPemasukan"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Chart Pengeluaran -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header"><h2>Pengeluaran</h2></div>
                            <div class="card-body">
                                <canvas id="chartPengeluaran"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="py-4 bg-light">
                <div class="container-fluid px-4">
                    <div class="d-flex justify-content-between small">
                        <div class="text-muted">Beauty Store</div>
                       
                    </div>
                </div>
            </footer>

        </div>
    </div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const pemasukan = <?php echo $data_pemasukan_json; ?>;
    const pengeluaran = <?php echo $data_pengeluaran_json; ?>;

    const lab_pem = pemasukan.map(e => e.tanggal);
    const val_pem = pemasukan.map(e => e.pemasukan);

    const lab_peng = pengeluaran.map(e => e.tanggal);
    const val_peng = pengeluaran.map(e => e.pengeluaran);

    /* ===== Chart Pemasukan ===== */
    new Chart(document.getElementById('chartPemasukan'), {
        type: 'line',
        data: {
            labels: lab_pem,
            datasets: [{
                label: 'Pemasukan',
                data: val_pem,
                borderColor: '#c2185b',
                backgroundColor: 'rgba(233, 30, 99, 0.3)',
                borderWidth: 2
            }]
        }
    });

    /* ===== Chart Pengeluaran ===== */
    new Chart(document.getElementById('chartPengeluaran'), {
        type: 'line',
        data: {
            labels: lab_peng,
            datasets: [{
                label: 'Pengeluaran',
                data: val_peng,
                borderColor: '#c2185b',
                backgroundColor: 'rgba(194, 24, 91, 0.3)',
                borderWidth: 2
            }]
        }
    });
});
</script>

</body>
</html>
