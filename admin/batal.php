<?php 
include "../db/koneksi.php";

$orders = query("SELECT * FROM orders 
                INNER JOIN jasa_kirim USING(id_jasa) 
                INNER JOIN metode USING(id_metode)
                INNER JOIN tb_user USING(id_user) 
                WHERE status = 'Dibatalkan' 
                ORDER BY status DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Pesanan Dibatalkan - Beauty Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"></script>

<!-- ===============================================
     🎀 PINK THEME BEAUTY STORE — SAME WITH DASHBOARD
     =============================================== -->
<style>
:root {
    --pink-primary: #e8a0b8ff;
    --pink-light: #ffebf2;
    --pink-bg: #ffe4ec;
    --pink-dark: #d88facff;
}

/* Body */
body.sb-nav-fixed {
    background-color: var(--pink-light) !important;
}

/* TOP NAVBAR */
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

/* SIDEBAR */
.sb-sidenav {
    background-color: var(--pink-primary) !important;
}
.sb-sidenav .nav-link,
.sb-sidenav .sb-sidenav-menu-heading {
    color: white !important;
}
.sb-sidenav .nav-link:hover {
    background-color: var(--pink-dark) !important;
}
.sb-sidenav .nav-link.active {
    background-color: var(--pink-dark) !important;
}

/* CARD */
.card {
    border-radius: 10px;
    background: white;
    border: none;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}
.card-header {
    background-color: var(--pink-primary) !important;
    color: white !important;
    font-weight: bold;
    border-radius: 10px 10px 0 0 !important;
}

/* Breadcrumb & Title */
h1, h2 {
    color: var(--pink-primary) !important;
}
.breadcrumb-item.active {
    color: var(--pink-primary) !important;
    font-weight: bold;
}

/* TABLE */
table thead tr {
    background: var(--pink-primary) !important;
    color: white !important;
}
table tbody tr:hover {
    background: var(--pink-bg) !important;
}

/* BUTTON */
.btn-primary {
    background: var(--pink-primary) !important;
    border-color: var(--pink-primary) !important;
}
.btn-primary:hover {
    background: var(--pink-dark) !important;
}

/* FOOTER */
footer.bg-light {
    background-color: var(--pink-bg) !important;
}
footer .text-muted {
    color: var(--pink-dark) !important;
}
</style>

</head>

<body class="sb-nav-fixed">

<!-- TOP NAVBAR -->
<nav class="sb-topnav navbar navbar-expand">
    <a class="navbar-brand ps-3" href="index.php">Admin Beauty</a>
    <button class="btn btn-link btn-sm me-4" id="sidebarToggle"><i class="fas fa-bars"></i></button>

    <ul class="navbar-nav ms-auto me-3">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" id="navbarDropdown" href="#" data-bs-toggle="dropdown">
                Admin
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>

<div id="layoutSidenav">

<!-- SIDEBAR -->
<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark">
        <div class="sb-sidenav-menu">
            <div class="nav">

                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href="index.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>

                <div class="sb-sidenav-menu-heading">Interface</div>

                <!-- Pesanan -->
                <a class="nav-link collapsed active" href="#" data-bs-toggle="collapse" data-bs-target="#pesanan">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Pesanan
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>

                <div class="collapse show" id="pesanan">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link active" href="batal.php">Pesanan Dibatalkan</a>
                        <a class="nav-link" href="konfirmasi.php">Konfirmasi Pesanan</a>
                        <a class="nav-link" href="diproses.php">Untuk Dikirim</a>
                        <a class="nav-link" href="selesai.php">Pesanan Selesai</a>
                        <a class="nav-link" href="refund.php">Pengajuan Refund</a>
                    </nav>
                </div>

                <!-- Produk -->
                <a class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#produk">
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

<!-- CONTENT -->
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <h1 class="mt-4">Pesanan Dibatalkan</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">List pesanan yang dibatalkan</li>
            </ol>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i> Data Pesanan Dibatalkan
                </div>

                <div class="card-body">
                    <table id="datatablesSimple" class="text-center table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama User</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($orders as $ord) { ?>
                            <tr>
                                <td><?= $ord["nama"] ?></td>
                                <td><?= $ord["tanggal"] ?></td>
                                <td><?= $ord["status"] ?></td>

                                <td>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal<?= $ord['id_orders'] ?>">
                                        Detail
                                    </button>

                                    <!-- Modal Detail -->
                                    <div class="modal fade" id="modal<?= $ord['id_orders'] ?>">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detail Pesanan</h5>
                                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">

                                                    <div class="container pb-3">
                                                        <div class="row border-bottom pb-2">
                                                            <div class="col-1"></div>
                                                            <div class="col-5">Nama Barang</div>
                                                            <div class="col">Jumlah</div>
                                                            <div class="col">Harga</div>
                                                        </div>

                                                        <?php
                                                            $ids = $ord["id_orders"];
                                                            $details = query("SELECT *, details.rating FROM details 
                                                                INNER JOIN orders USING(id_orders)
                                                                INNER JOIN tb_barang USING(id_barang)
                                                                INNER JOIN jasa_kirim USING(id_jasa)
                                                                INNER JOIN metode USING(id_metode)
                                                                WHERE id_orders = '$ids'");

                                                            $total = 0;
                                                            $total_harga = 0;

                                                            foreach ($details as $dt) {
                                                                $total_harga += $dt["harga_satuan"] * $dt["qty"];
                                                                $total += $dt["harga_satuan"] * $dt["qty"];
                                                                $total += $dt["harga_jasa"] + $dt["harga_admin"];
                                                        ?>

                                                        <div class="row pt-3">
                                                            <div class="col-1">
                                                                <img src="../<?= $dt['img'] ?>" width="50" height="50">
                                                            </div>
                                                            <div class="col-5 text-start"><?= $dt["nama_barang"] ?></div>
                                                            <div class="col"><?= $dt["qty"] ?></div>
                                                            <div class="col"><?= rupiah($dt["harga_satuan"]) ?></div>
                                                        </div>

                                                        <?php } ?>
                                                    </div>

                                                    <div class="container border-top pt-3">
                                                        <div class="row">
                                                            <div class="col-6"></div>
                                                            <div class="col">
                                                                <b>Total Pesanan</b>
                                                            </div>
                                                            <div class="col">
                                                                <?= rupiah($total) ?>
                                                            </div>
                                                        </div>

                                                        <div class="row pt-2">
                                                            <div class="col-6"></div>
                                                            <div class="col">Jasa Kirim</div>
                                                            <div class="col"><?= $dt["nama_jasa"] ?></div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </td>

                            </tr>
                        <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between small">
                <div class="text-muted">Beauty Store</div>
              
            </div>
        </div>
    </footer>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
<script src="../js/datatables-simple-demo.js"></script>

</body>
</html>
