<?php 
    include "../db/koneksi.php";
    $orders = query("SELECT *, tb_refund.tanggal as tgl_refund, tb_refund.alasan FROM orders 
                inner join jasa_kirim using(id_jasa) 
                inner join metode using(id_metode)
                inner join tb_user using(id_user) 
                left join tb_refund using(id_orders)
                where orders.status = 'Diajukan Retur' OR tb_refund.status_refund = 'Diterima' OR tb_refund.status_refund = 'Ditolak' 
                ORDER BY orders.status DESC");

    if(isset($_POST["confirm"])){
        if (confirm_retur($_POST) > 0 ) {
            echo "<script>
                alert('Refund Dikonfirmasi');
                window.location.href='konfirmasi.php';
            </script>";
        } else {
            echo "<script>alert('Refund Gagal Dikonfirmasi');</script>";
        }
    }
    
    if(isset($_POST["batal"])){
        if (tolak_retur($_POST) > 0 ) {
            echo "<script>
                alert('Pesanan Dibatalkan');
                window.location.href='konfirmasi.php'; 
            </script>";
        } else {
            echo "<script>alert('Pesanan Gagal Dibatalkan');</script>";
        }
    }
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
            🎀 PINK THEME — SAMA PERSIS SEPERTI konfirmasi.php
------------------------------------------------------- -->
<style>
:root {
    --pink-primary: #e8a0b8ff;
    --pink-light: #ffebf2;
    --pink-bg: #ffe4ec;
    --pink-dark: #d88facff;
}

/* BODY */
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
    background: white;
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

/* BUTTONS */
.btn-primary {
    background: var(--pink-primary) !important;
    border-color: var(--pink-primary) !important;
}
.btn-primary:hover {
    background: var(--pink-dark) !important;
}

.btn-success {
    background: #4CAF50 !important;
}
.btn-danger {
    background: #d9534f !important;
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

    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <ul class="navbar-nav ms-auto me-3">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" data-bs-toggle="dropdown">
                Admin
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>

<!-- SIDEBAR -->
<div id="layoutSidenav">
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
                    <a class="nav-link collapsed active" data-bs-toggle="collapse" data-bs-target="#collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                        Pesanan
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse show" id="collapseLayouts">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="batal.php">Pesanan Dibatalkan</a>
                            <a class="nav-link" href="konfirmasi.php">Konfirmasi Pesanan</a>
                            <a class="nav-link" href="diproses.php">Untuk Dikirim</a>
                            <a class="nav-link" href="selesai.php">Pesanan Selesai</a>
                            <a class="nav-link active" href="refund.php">Pengajuan Refund</a>
                        </nav>
                    </div>

                    <a class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#collapsePages">
                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                        Manajemen Produk
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse" id="collapsePages">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="all_product.php">Lihat Semua Produk</a>
                            <a class="nav-link" href="edit_product.php">Edit & Hapus Produk</a>
                            <a class="nav-link" href="add_product.php">Tambah Produk & Stok</a>
                        </nav>
                    </div>

                    <div class="sb-sidenav-menu-heading">Addons</div>
                    <a class="nav-link" href="pendapatan.php"><div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>Pemasukan</a>
                    <a class="nav-link" href="pengeluaran.php"><div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>Pengeluaran</a>

                </div>
            </div>
        </nav>
    </div>

    <!-- CONTENT -->
    <div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <h1 class="mt-4" style="color: var(--pink-primary)">Pesanan</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active" style="color: var(--pink-primary)">Pengajuan Refund</li>
            </ol>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Pesanan Di Beauty Store
                </div>

                <div class="card-body">
                    <table id="datatablesSimple" class="text-center">
                        <thead>
                            <tr>
                                <th>Nama User</th>
                                <th>Tanggal</th>
                                <th>Status Order</th>
                                <th>Status Refund</th>
                                <th>Detail</th>
                                <th>Konfirmasi</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($orders as $ord){ ?>
                            <tr>
                                <td><p style="font-size:16px"><?= $ord["nama"] ?></p></td>
                                <td><?= $ord["tgl_refund"] ?></td>
                                <td><?= $ord["status"] ?></td>
                                <td><?= $ord["status_refund"] ?></td>

                                <td>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $ord['id_orders'] ?>">Detail</button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal<?= $ord["id_orders"] ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5">Detail Pesanan</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <!-- isi modal tetap sama persis -->
                                                    <?php 
                                                        $ids = $ord["id_orders"];
                                                        $details = query("SELECT * FROM details 
                                                        inner join orders using(id_orders)
                                                        inner join tb_barang using(id_barang)
                                                        inner join jasa_kirim using(id_jasa) 
                                                        inner join metode using(id_metode)
                                                        where id_orders = '$ids'");

                                                        include "template_detail_refund.php"; // optional
                                                    ?>
                                                </div>

                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <?php if ($ord["status"] == "Diajukan Retur") { ?>
                                    <form method="post">
                                        <input type="hidden" name="id_orders" value="<?= $ord["id_orders"] ?>">

                                        <button type="submit" onclick="return confirm('Yakin untuk melakukan refund?')" class="btn btn-success" name="confirm">Konfirmasi</button>

                                        <button type="submit" onclick="return confirm('Yakin untuk membatalkan refund?')" class="btn btn-danger" name="batal">Batal</button>
                                    </form>
                                    <?php } ?>
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
<script src="../js/scripts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
<script src="../js/datatables-simple-demo.js"></script>

</body>
</html>
