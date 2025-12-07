<?php
session_start();
include __DIR__ . '/db/koneksi.php';

// Pastikan user login
if (!isset($_SESSION['id_user'])) {
    header('Location: login.php?redirect=cart.php');
    exit;
}

$userId = intval($_SESSION['id_user']);

// Ambil semua item di keranjang bersama data produk
$stmt = $conn->prepare("SELECT k.id_keranjang, k.id_user, k.id_barang, k.qty, b.nama_barang, b.harga, b.img FROM keranjang k JOIN tb_barang b ON k.id_barang = b.id_barang WHERE k.id_user = ? ORDER BY k.id_keranjang DESC");
$stmt->bind_param('i', $userId);
$stmt->execute();
$cartItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Hitung jumlah item total untuk navbar
$cartCount = 0;
foreach ($cartItems as $item) $cartCount += intval($item['qty']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Keranjang - Beauty Store</title>
<!-- no longer using static productdata.js; cart items rendered from DB below -->
<style>
body { font-family:'Times New Roman', serif; margin:0; background:#fff; color:#333; }
.navbar { display:flex; justify-content:space-between; align-items:center; padding:15px 30px; background:#fff; box-shadow:0 2px 5px rgba(0,0,0,0.1);}
.navbar .logo { font-size:24px; font-weight:bold; color:#e91e63; }
.navbar ul { margin:0; padding:0; display:flex; list-style:none; gap:25px;}
.navbar ul li a { text-decoration:none; color:#333; font-weight:500; }
.navbar ul li a:hover { color:#e91e63; }
.shop-btn { background:#e91e63; color:#fff; padding:8px 18px; border-radius:20px; text-decoration:none; position:relative;}
.shop-btn:hover { background:#c2185b; }
#navCartCount { background:#fff; color:#e91e63; padding:2px 6px; border-radius:50%; font-size:12px; position:absolute; top:-5px; right:-5px; }

.container { max-width:1000px; margin:30px auto; padding:0 20px; }
h2 { color:#e91e63; margin-bottom:20px; }
.cart-table { width:100%; border-collapse:collapse; }
.cart-table th, .cart-table td { padding:12px; border-bottom:1px solid #eee; text-align:left; }
.cart-table img { width:80px; height:80px; object-fit:cover; border-radius:8px; }
input.qty-input { width:60px; padding:4px; border-radius:6px; border:1px solid #ccc; text-align:center; }
button.remove-btn { background:#e91e63; color:#fff; border:none; padding:6px 10px; border-radius:8px; cursor:pointer; }
button.remove-btn:hover { background:#c2185b; }
.total { text-align:right; font-weight:bold; margin-top:15px; font-size:18px; }
.checkout-btn { display:inline-block; background:#e91e63; color:#fff; padding:10px 20px; border-radius:25px; text-decoration:none; margin-top:10px;}
.checkout-btn:hover { background:#c2185b; }
</style>
</head>
<body>
<div class="navbar">
    <div class="logo">Beauty Store</div>
    <ul>
        <li><a href="index.php">Beranda</a></li>
        <li><a href="products.php">Produk</a></li>
        <li><a href="about.php">Tentang</a></li>
        <li><a href="contact.php">Kontak</a></li>
    </ul>
    <a class="shop-btn" href="cart.php">Keranjang 🛒 <span id="navCartCount"><?= $cartCount ?></span></a>
</div>

<div class="container">
<h2>Keranjang Belanja</h2>
<div id="cartContainer"></div>
</div>

<script>
// Data keranjang dari PHP
const cartItems = <?= json_encode($cartItems) ?>;

// Format Rupiah
function formatRupiah(num){
    return new Intl.NumberFormat('id-ID', { style:'currency', currency:'IDR' }).format(num);
}

// Render cart
function renderCart(){
    const container = document.getElementById('cartContainer');
    if(cartItems.length === 0){
        container.innerHTML = '<p>Keranjang kosong. <a href="products.php">Belanja sekarang</a></p>';
        return;
    }

    let html = `<table class="cart-table">
        <thead>
            <tr>
                <th>Pilih</th>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>`;

    cartItems.forEach(item => {
        const subtotal = parseFloat(item.harga) * parseInt(item.qty);
        item.subtotal = subtotal;
        html += `<tr>
            <td><input type="checkbox" class="select-item" data-id="${item.id_keranjang}" checked></td>
            <td style="display:flex; gap:10px; align-items:center;">
                <img src="${item.img || 'image/placeholder.png'}" alt="${item.nama_barang}">
                <span>${item.nama_barang}</span>
            </td>
            <td data-price="${item.harga}">${formatRupiah(item.harga)}</td>
            <td><input type="number" class="qty-input" min="1" value="${item.qty}" data-id="${item.id_keranjang}" data-price="${item.harga}"></td>
            <td class="item-subtotal" data-subtotal="${subtotal}">${formatRupiah(subtotal)}</td>
            <td><button class="remove-btn" data-id="${item.id_keranjang}">Hapus</button></td>
        </tr>`;
    });

    html += `</tbody></table>`;
    html += `<div class="total">Total yang dipilih: <span id="cartTotalDisplay"></span></div>`;
    html += `<button class="checkout-btn" id="checkoutBtn">Checkout Produk Terpilih</button>`;
    container.innerHTML = html;

    updateTotal();

    // Event listeners
    document.querySelectorAll('.qty-input').forEach(input => input.addEventListener('change', e => updateQty(e.target)));
    document.querySelectorAll('.remove-btn').forEach(btn => btn.addEventListener('click', e => removeItem(e.target.dataset.id)));
    document.querySelectorAll('.select-item').forEach(cb => cb.addEventListener('change', updateTotal));
}

// Hitung total berdasarkan checkbox dan data-price
function updateTotal(){
    let total = 0;
    document.querySelectorAll('.select-item').forEach(cb => {
        if(cb.checked){
            const row = cb.closest('tr');
            const qty = parseInt(row.querySelector('.qty-input').value);
            const price = parseInt(row.querySelector('td[data-price]').dataset.price);
            total += qty * price;
        }
    });
    document.getElementById('cartTotalDisplay').textContent = formatRupiah(total);
}

// Update quantity
function updateQty(input){
    const id = input.dataset.id;
    const qty = parseInt(input.value);
    fetch('update_cart_qty.php',{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:`id_keranjang=${id}&qty=${qty}`
    }).then(r=>r.json()).then(res=>{
        if(res.success){
            const item = cartItems.find(i => i.id_keranjang == id);
            if(item) item.qty = qty;
            renderCart();
            document.getElementById('navCartCount').textContent = res.cartCount;
        } else alert(res.message);
    }).catch(()=>alert('Terjadi kesalahan'));
}

// Remove item
function removeItem(id){
    if(!confirm('Hapus produk dari keranjang?')) return;
    fetch('remove_from_cart.php',{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:`id_keranjang=${id}`
    }).then(r=>r.json()).then(res=>{
        if(res.success){
            const idx = cartItems.findIndex(i => i.id_keranjang==id);
            if(idx!==-1) cartItems.splice(idx,1);
            renderCart();
            document.getElementById('navCartCount').textContent = res.cartCount;
        } else alert(res.message);
    }).catch(()=>alert('Terjadi kesalahan'));
}

// Checkout produk terpilih
document.addEventListener('click', e=>{
    if(e.target.id === 'checkoutBtn'){
        const selectedIds = [];
        document.querySelectorAll('.select-item:checked').forEach(cb => selectedIds.push(cb.dataset.id));
        if(selectedIds.length === 0){
            alert('Silakan pilih produk untuk checkout.');
            return;
        }
        // Redirect ke checkout dengan id_keranjang terpilih
        window.location.href = 'checkout.php?ids=' + selectedIds.join(',');
    }
});

renderCart();
</script>
</body>
</html>
