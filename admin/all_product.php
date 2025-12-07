<?php 
    include "../db/koneksi.php";
    $barang = query("SELECT *,
                (SELECT AVG(rating) FROM details WHERE details.id_barang = tb_barang.id_barang AND rating != 0) AS average_rating
                FROM tb_barang
                ORDER BY average_rating DESC, terjual DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Dashboard Beauty</title>

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"></script>

<!-- ------------------------------------------------------
            🎀 PINK THEME — SAMA SEPERTI konfirmasi.php
------------------------------------------------------- -->
<style>
:root {
    --pink-primary: #e8a0b8ff;
    --pink-light: #ffebf2;
    --pink-bg: #ffe4ec;
    --pink-dark: #d88facff;
}

body.sb-nav-fixed {
    background-color: var(--pink-light) !important;
}

/* NAVBAR */
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
    border: none;
    background: white !important;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}
.card-header {
    background-color: var(--pink-primary) !important;
    color: white !important;
    font-weight: bold;
}

/* TABLE */
table thead tr {
    background-color: var(--pink-primary) !important;
    color: white !important;
}
table tbody tr:hover {
    background-color: var(--pink-bg) !important;
}

/* BUTTON */
.btn-primary {
    background: var(--pink-primary) !important;
    border-color: var(--pink-primary) !important;
}
.btn-primary:hover {
    background: var(--pink-dark) !important;
}

.btn-secondary {
    background: #999 !important;
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

<!-- NAVBAR -->
<nav class="sb-topnav navbar navbar-expand">
    <a class="navbar-brand ps-3" href="index.php">Admin Beauty</a>
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4" id="sidebarToggle"><i class="fas fa-bars"></i></button>

    <ul class="navbar-nav ms-auto me-3">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" data-bs-toggle="dropdown">Admin</a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#!">Logout</a></li>
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
                    <a class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                        Pesanan
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse" id="collapseLayouts">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="batal.php">Pesanan Dibatalkan</a>
                            <a class="nav-link" href="konfirmasi.php">Konfirmasi Pesanan</a>
                            <a class="nav-link" href="diproses.php">Untuk Dikirim</a>
                            <a class="nav-link" href="selesai.php">Pesanan Selesai</a>
                            <a class="nav-link" href="refund.php">Pengajuan Refund</a>
                        </nav>
                    </div>

                    <a class="nav-link active collapsed" data-bs-toggle="collapse" data-bs-target="#collapsePages">
                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                        Manajemen Produk
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse show" id="collapsePages">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link active" href="all_product.php">Lihat Semua Produk</a>
                            <a class="nav-link" href="edit_product.php">Edit & Hapus Produk</a>
                            <a class="nav-link" href="add_product.php">Tambah Produk & Stok</a>
                        </nav>
                    </div>

                    <div class="sb-sidenav-menu-heading">Addons</div>
                    <a class="nav-link" href="pendapatan.php"><div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>Pemasukan</a>
                    <a class="nav-link" href="pengeluaran.php"><div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>Pengeluaran</a>
                    <a class="nav-link" href="user.php"><div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>Pelanggan</a>
                </div>
            </div>
        </nav>
    </div>

    <!-- CONTENT -->
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">

                <h1 class="mt-4" style="color: var(--pink-primary)">Manajemen Produk</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active" style="color: var(--pink-primary)">All Produk</li>
                </ol>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Barang Di Beauty Store
                    </div>

                    <div class="card-body">
                        <table id="datatablesSimple">
                            <thead>
                                <tr>
                                    <th>Nama barang</th>
                                    <th>Harga</th>
                                    <th>Kategori</th>
                                    <th>Merk</th>
                                    <th>Stok</th>
                                    <th>Rating</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php 
                            foreach ($barang as $brg){
                                $average_rating = round($brg['average_rating'], 1);
                                if ($average_rating === null) $average_rating = 0;
                            ?>
                            <tr>
                                <td><?= substr($brg["nama_barang"], 0, 35) . "..." ?></td>
                                <td><?= rupiah($brg["harga"]) ?></td>
                                <td><?= $brg["kategori"] ?></td>
                                <td><?= $brg["merk"] ?></td>
                                <td><?= $brg["stok"] ?></td>

                                <td>
                                    <p style="font-size:12px">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fa<?= $i <= $average_rating ? 's' : 'r' ?> fa-star" style="color:#ffc42e;"></i>
                                        <?php endfor; ?>
                                    </p>
                                </td>

                                <td>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $brg['id_barang'] ?>">Detail</button>

                                    <!-- Modal Detail -->
                                    <div class="modal fade" id="exampleModal<?= $brg["id_barang"] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5">Detail Barang</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="container">
                                                        <div class="row">
                                                            <div class="col-2">
                                                                <img src="../<?= $brg["img"] ?>" width="75" height="75">
                                                            </div>
                                                            <div class="col">
                                                                <p style="font-size:19px; margin-left:15px"><?= $brg["nama_barang"] ?></p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="container pt-2">
                                                        <h5>Deskripsi</h5>
                                                        <?= nl2br($brg["keterangan"]) ?>
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
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Beauty Store</div>
                </div>
            </div>
        </footer>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/scripts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
<script src="../js/datatables-simple-demo.js"></script>

</body>
</html>
