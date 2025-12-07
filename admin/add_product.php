<?php 
    include "../db/koneksi.php";
    $barang = query("SELECT * FROM tb_barang order by id_barang desc");

    if (isset($_POST["add_stok"])) {
        if (!empty($_POST["stok"])) {
            if (add_stok($_POST)) {
                if (pengeluaranstok($_POST)){
                    echo "<script>
                        alert('Stok berhasil ditambahkan');
                        window.location.href = 'add_product.php'; 
                    </script>";
                } else {
                    echo "<script>
                        alert('Gagal menambahkan pengeluaran');
                        history.back();
                    </script>";
                }
            } else {
                echo "<script>
                    alert('Gagal menambahkan stok');
                    history.back();
                </script>";
            }
        } else {
            echo "<script>
                alert('Stok tidak boleh kosong.');
                history.back();
            </script>";
        }
    }

    if (isset($_POST["add_produk"])) {
        if (!empty($_POST["nama_barang"]) && !empty($_POST["kategori"]) && 
            !empty($_POST["merk"]) && !empty($_POST["harga"]) && 
            !empty($_FILES["img"]) && !empty($_POST["stok"]) && 
            !empty($_POST["keterangan"])) {

            if ($_POST["harga"] <= 0 || $_POST["stok"] <= 0) {
                echo "<script>
                    alert('Harga dan stok harus bernilai positif');
                    history.back();
                </script>";
            } else {
                $id_barang = add_produk($_POST, $_FILES);
                if ($id_barang) {
                    if (add_pengeluaran($_POST, $id_barang)) {
                        echo "<script>
                            alert('Barang berhasil ditambahkan');
                            window.location.href = 'add_product.php'; 
                        </script>";
                    } else {
                        echo "<script>
                            alert('Gagal menambahkan pengeluaran');
                            history.back();
                        </script>";
                    }
                } else {
                    echo "<script>
                        alert('Gagal menambahkan barang');
                        history.back();
                    </script>";
                }
            }
        } else {
            echo "<script>
                alert('Form tidak lengkap. Mohon isi semua field.');
                history.back();
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
<title>Dashboard Admin Beauty</title>

<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
<link href="../css/styles.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"></script>

<!-- ===================== -->
<!--  THEME PINK SAMA SELESAI.PHP -->
<!-- ===================== -->
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

/* BUTTON */
.btn-primary {
    background: var(--pink-primary) !important;
    border-color: var(--pink-primary) !important;
}
.btn-primary:hover {
    background: var(--pink-dark) !important;
}
.btn-success {
    background: var(--pink-primary) !important;
    border-color: var(--pink-primary) !important;
}
.btn-success:hover {
    background: var(--pink-dark) !important;
    border-color: var(--pink-dark) !important;
}

/* MODAL HEADER */
.modal-header {
    background: var(--pink-primary) !important;
    color: white !important;
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
<nav class="sb-topnav navbar navbar-expand navbar-dark">
    <a class="navbar-brand ps-3" href="index.php">Admin Beauty</a>
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle"><i class="fas fa-bars"></i></button>

    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" data-bs-toggle="dropdown">Admin</a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <!-- Sidebar menu sama persis, warna sudah tema pink -->
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

                    <a class="nav-link active collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages">
                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                        Manajemen Produk
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse show" id="collapsePages">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="all_product.php">Lihat Semua Produk</a>
                            <a class="nav-link" href="edit_product.php">Edit & Hapus Produk</a>
                            <a class="nav-link active" href="add_product.php">Tambah Produk & Stok</a>
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

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4" style="color: var(--pink-primary)">Manajemen Produk</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active" style="color: var(--pink-primary)">Tambah Produk & Stok</li>
                </ol>

                <!-- Card & Table tetap sama -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Barang Di Beauty Store
                    </div>
                    <div class="card-body">
                        <table id="datatablesSimple" class="text-center table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama barang</th>
                                    <th>Harga</th>
                                    <th>Kategori</th>
                                    <th>Merk</th>
                                    <th>Stok</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($barang as $brg): ?>
                                <tr>
                                    <td><?= $brg["nama_barang"] ?></td>
                                    <td><?= rupiah($brg["harga"]) ?></td>
                                    <td><?= $brg["kategori"] ?></td>
                                    <td><?= $brg["merk"] ?></td>
                                    <td><?= $brg["stok"] ?></td>
                                    <td>
                                        <!-- Modal & Button tetap sama -->
                                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal<?= $brg['id_barang'] ?>">Tambah Stok</button>
                                        <div class="modal fade" id="modal<?= $brg['id_barang'] ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5">Tambah Stok Barang</h1>
                                                        <button class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="post">
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-2">
                                                                    <img src="../<?= $brg["img"] ?>" width="75" height="75">
                                                                </div>
                                                                <div class="col">
                                                                    <p style="font-size:19px;margin-left:15px"><?= $brg["nama_barang"] ?></p>
                                                                </div>
                                                            </div>
                                                            <div class="pt-3">
                                                                <input type="hidden" name="id_barang" value="<?= $brg["id_barang"] ?>">
                                                                <input type="hidden" name="harga" value="<?= $brg["harga"] ?>">
                                                                <label><p style="font-size:18px">Tambah Stok :</p></label>
                                                                <input class="form-control" type="number" name="stok" min="1">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button class="btn btn-success" name="add_stok">Tambah Stok</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <!-- Button Tambah Barang -->
                        <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            Tambah Barang
                        </button>

                        <div class="modal fade" id="modalTambah" data-bs-backdrop="static">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5">Form Tambah Barang</h1>
                                        <button class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" enctype="multipart/form-data">
                                            <!-- Form tetap sama -->
                                            <label><p style="font-size:18px">Nama Barang :</p></label>
                                            <input type="text" name="nama_barang" class="form-control">
                                            <label class="pt-3"><p style="font-size:18px">Kategori Barang :</p></label>
                                            <select name="kategori" class="form-control">
                                                <option value="">-- Pilih Kategori --</option>
                                                <option value="Skincare">Skincare</option>
                                                <option value="Makeup">Makeup</option>
                                                <option value="Body Care">Body Care</option>
                                                <option value="Hair Care">Hair Care</option>
                                                <option value="Sunscreen">Sunscreen</option>
                                                <option value="Facial Wash">Facial Wash</option>
                                                <option value="Serum">Serum</option>
                                                <option value="Tonner">Toner</option>
                                                <option value="Lip Care">Lip Care</option>
                                                <option value="Mask">Mask</option>
                                            </select>
                                            <label class="pt-3"><p style="font-size:18px">Merk Barang :</p></label>
                                            <select name="merk" class="form-control">
                                                <option value="">-- Pilih Merk --</option>
                                                <option value="Wardah">Wardah</option>
                                                <option value="Emina">Emina</option>
                                                <option value="Somethinc">Somethinc</option>
                                                <option value="Azarine">Azarine</option>
                                                <option value="MS Glow">MS Glow</option>
                                                <option value="Scarlett">Scarlett</option>
                                                <option value="Garnier">Garnier</option>
                                                <option value="Nivea">Nivea</option>
                                                <option value="Pond's">Pond's</option>
                                                <option value="Make Over">Make Over</option>
                                                <option value="Innisfree">Innisfree</option>
                                                <option value="Hada Labo">Hada Labo</option>
                                                <option value="The Originote">The Originote</option>
                                                <option value="Skintific">Skintific</option>
                                                <option value="YOU">YOU</option>
                                                <option value="Implora">Implora</option>
                                            </select>
                                            <label class="pt-3"><p style="font-size:18px">Harga Barang :</p></label>
                                            <input type="number" name="harga" class="form-control" min="1">
                                            <label class="pt-3"><p style="font-size:18px">Gambar Barang :</p></label>
                                            <input type="file" name="img" class="form-control">
                                            <label class="pt-3"><p style="font-size:18px">Stok Barang :</p></label>
                                            <input type="number" name="stok" class="form-control" min="1">
                                            <label class="pt-3"><p style="font-size:18px">Keterangan / Deskripsi Barang :</p></label>
                                            <textarea name="keterangan" class="form-control" rows="5"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button class="btn btn-primary" name="add_produk">Tambah Barang</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>

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
