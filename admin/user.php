<?php 
    include "../db/koneksi.php";
    $user = query("SELECT * FROM tb_user");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Dashboard Beauty</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"></script>

    <!-- PINK THEME sesuai selesai.php -->
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
    }

    table thead tr {
        background-color: var(--pink-primary) !important;
        color: white !important;
    }
    table tbody tr:hover {
        background-color: var(--pink-bg) !important;
    }

    .btn-primary {
        background: var(--pink-primary) !important;
        border-color: var(--pink-primary) !important;
    }
    .btn-primary:hover {
        background: var(--pink-dark) !important;
    }

    footer.bg-light {
        background-color: var(--pink-bg) !important;
    }
    footer .text-muted {
        color: var(--pink-dark) !important;
    }
    </style>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand">
        <a class="navbar-brand ps-3" href="index.php">Admin Beauty</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle"><i class="fas fa-bars"></i></button>
        <ul class="navbar-nav ms-auto me-3">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Admin</a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Interface</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts">
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

                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Manajemen Produk
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse show" id="collapsePages">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link " href="all_product.php">Lihat Semua Produk</a>
                                <a class="nav-link" href="edit_product.php">Edit & Hapus Produk</a>
                                <a class="nav-link" href="add_product.php">Tambah Produk & Stok</a>
                            </nav>
                        </div>

                        <div class="sb-sidenav-menu-heading">Addons</div>
                        <a class="nav-link" href="pendapatan.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Pemasukan
                        </a>
                        <a class="nav-link" href="pengeluaran.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Pengeluaran
                        </a>
                        <a class="nav-link active" href="user.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Pelanggan
                        </a>
                    </div>
                </div>
            </nav>
        </div>

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
                                        <th>Nama User</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Alamat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($user as $us) { ?>
                                        <tr>
                                            <td><?= $us["nama"] ?></td>
                                            <td><?= $us["username"]?></td>
                                            <td><?= $us["email"] ?></td>
                                            <td><?= $us["alamat"] ?></td>
                                        </tr>
                                    <?php }?>
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
                        <div>
                        </div>
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
