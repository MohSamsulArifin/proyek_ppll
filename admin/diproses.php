<?php 
    include "../db/koneksi.php";
    
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
    
    $orders = query("SELECT * FROM orders 
                INNER JOIN jasa_kirim USING(id_jasa) 
                INNER JOIN metode USING(id_metode)
                INNER JOIN tb_user USING(id_user) 
                WHERE status = 'Diproses' OR status = 'Dikirim' 
                ORDER BY status DESC");

    if(isset($_POST["kirim"])){
        $id_orders_post = isset($_POST['id_orders']) ? intval($_POST['id_orders']) : 0;
        $resi_post = isset($_POST['resi']) ? trim($_POST['resi']) : null;
        if (kirim($id_orders_post, $resi_post)) {
            echo "<script>
                    alert('Pesanan Dikirim');
                    window.location.href='diproses.php';
                </script>";
        } else {
            echo "<script>
                    alert('Pesanan Gagal Dikirim');
                </script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Diproses - Beauty Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"></script>

<!-- ===============================================
     🎀 PINK THEME — SAMA PERSIS DENGAN konfirmasi.php
     =============================================== -->
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
    background: white;
    border: none;
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
    background: #5cb85c !important;
    border-color: #5cb85c !important;
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
    <button class="btn btn-link btn-sm me-4" id="sidebarToggle"><i class="fas fa-bars"></i></button>

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

                <a class="nav-link collapsed active" data-bs-toggle="collapse" data-bs-target="#pesanan">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Pesanan
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>

                <div class="collapse show" id="pesanan">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="batal.php">Pesanan Dibatalkan</a>
                        <a class="nav-link" href="konfirmasi.php">Konfirmasi Pesanan</a>
                        <a class="nav-link active" href="diproses.php">Untuk Dikirim</a>
                        <a class="nav-link" href="selesai.php">Pesanan Selesai</a>
                        <a class="nav-link" href="refund.php">Pengajuan Refund</a>
                    </nav>
                </div>

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
                <a class="nav-link" href="pendapatan.php"><div class="sb-nav-link-icon"><i class="fas fa-money-bill"></i></div>Pemasukan</a>
                <a class="nav-link" href="pengeluaran.php"><div class="sb-nav-link-icon"><i class="fas fa-wallet"></i></div>Pengeluaran</a>
                <a class="nav-link" href="user.php"><div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>Pelanggan</a>

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
            <li class="breadcrumb-item active" style="color: var(--pink-primary)">Untuk Dikirim</li>
        </ol>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i> Pesanan Di Beauty Store
            </div>

            <div class="card-body">
                <table id="datatablesSimple" class="text-center table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama User</th>
                            <th>Status</th>
                            <th>Bukti Pembayaran</th>
                            <th>Resi</th>
                            <th>Konfirmasi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($orders as $ord) { ?>
                        <tr>
                            <td><?= $ord["nama"] ?></td>
                            <td><?= $ord["status"] ?></td>

                            <td>
                                <?php if(empty($ord["bukti_pembayaran"])) { ?>
                                    <img src="../assets/icon/x.png" height="20">
                                <?php } else { ?>
                                    <img src="../assets/icon/check.png" height="25">
                                <?php } ?>
                            </td>

                            <td>
                                <?php if (!empty($ord['resi'])): ?>
                                    <div style="font-size:13px;color:#198754;font-weight:600;"><?= htmlspecialchars($ord['resi']) ?></div>
                                <?php else: ?>
                                    <div style="font-size:13px;color:#6c757d;">Belum ada resi</div>
                                <?php endif; ?>
                                <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#modal<?= $ord['id_orders'] ?>">Cek Resi</button>

                                <!-- MODAL -->
                                <div class="modal fade" id="modal<?= $ord['id_orders'] ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title">Detail Pesanan</h5>
                                                <button class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <div id="print<?= $ord['id_orders'] ?>">
                                                    <div class="container pb-3">
                                                        <div class="row mb-3">
                                                            <div class="col-12">
                                                                <strong>Order ID:</strong> <?= $ord['id_orders'] ?>
                                                            </div>
                                                        </div>

                                                        <div class="row border-bottom pb-2">
                                                            <div class="col-1"></div>
                                                            <div class="col-5">Nama Barang</div>
                                                            <div class="col">Jumlah</div>
                                                            <div class="col">Harga</div>
                                                        </div>

                                                        <?php
                                                            $ids = $ord["id_orders"];
                                                            $details = query("SELECT d.*, tb_barang.nama_barang, tb_barang.img, j.nama_jasa, m.nama_metode, m.harga_admin FROM details d INNER JOIN tb_barang ON d.id_barang = tb_barang.id_barang INNER JOIN orders o ON d.id_orders = o.id_orders INNER JOIN jasa_kirim j ON o.id_jasa = j.id_jasa INNER JOIN metode m ON o.id_metode = m.id_metode WHERE d.id_orders = '$ids'");

                                                            $total = 0;
                                                            foreach ($details as $dt) {
                                                                $total += $dt["harga_satuan"] * $dt["qty"];
                                                        ?>

                                                        <div class="row pt-3">
                                                            <div class="col-1">
                                                                <img src="../<?= $dt['img'] ?>" width="50" height="50">
                                                            </div>
                                                            <div class="col-5 text-start"><?= htmlspecialchars($dt["nama_barang"]) ?></div>
                                                            <div class="col"><?= $dt["qty"] ?></div>
                                                            <div class="col"><?= rupiah($dt["harga_satuan"]) ?></div>
                                                        </div>

                                                        <?php } ?>

                                                        <div class="container border-top pt-3">
                                                            <div class="row">
                                                                <div class="col-6"></div>
                                                                <div class="col"><b>Total Pesanan</b></div>
                                                                <div class="col"><?= rupiah($total) ?></div>
                                                            </div>
                                                        </div>

                                                        <div class="row pt-3">
                                                            <div class="col-12">
                                                                <h5>Bukti Pembayaran</h5>
                                                                <?php if (!empty($ord['bukti_pembayaran'])): ?>
                                                                    <img src="../<?= htmlspecialchars($ord['bukti_pembayaran']) ?>" alt="Bukti" style="max-width:300px;border:1px solid #eee;padding:6px;border-radius:6px;" />
                                                                <?php else: ?>
                                                                    <div class="text-muted">Belum ada bukti pembayaran.</div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <form method="post" class="d-flex w-100 align-items-center justify-content-between">
                                                    <div style="flex:1;padding-right:10px;">
                                                        <label style="font-weight:600">No. Resi / Tracking</label>
                                                        <input type="text" name="resi" value="<?= htmlspecialchars($ord['resi'] ?? '') ?>" class="form-control" placeholder="Masukkan no. resi / tracking" />
                                                        <input type="hidden" name="id_orders" value="<?= $ord['id_orders'] ?>">
                                                    </div>
                                                    <div style="white-space:nowrap;">
                                                        <button type="submit" name="kirim" class="btn btn-success" onclick="return confirm('Simpan resi dan tandai sebagai Dikirim?')">Simpan & Kirim</button>
                                                        <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <?php if (!empty($ord["bukti_pembayaran"]) && $ord["status"] == 'Diproses') { ?>
                                <form method="post">
                                    <input type="hidden" name="id_orders" value="<?= $ord['id_orders'] ?>">
                                    <button type="submit" class="btn btn-success" name="kirim" onclick="return confirm('Yakin untuk mengirim pesanan?')">Kirim</button>
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

<!-- FOOTER -->
<footer class="py-4 bg-light mt-auto">
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between small">
            <div class="text-muted">Beauty Store</div>
        </div>
    </div>
</footer>

</div>

</div>

<script>
function printModalContent(orderId) {
    var printContents = document.getElementById('print' + orderId).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    window.location.reload();
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
<script src="../js/datatables-simple-demo.js"></script>

</body>
</html>
