Admin image updates
1) Login to the admin panel and go to 'Edit Produk' (admin/edit_product.php) to update product images. Use the 'Gambar Produk' field to upload a new image — it will be stored under `assets/img/` and used by the public pages.
2) After updating, check `products.php` and `productdetail.php` to confirm the new image shows.

Manual test steps: Add to cart from product detail

1) Make sure XAMPP/Apache + MySQL is running and `beauty` database is loaded.
2) Ensure `db/koneksi.php` credentials match your MySQL setup.
3) Open the site in browser using the PHP files, e.g. http://localhost/ppll/Beauty/index.php
4) Go to a product detail page (click a product from `products.php` or open `productdetail.php?id=<id>`).
5) On the product page: change the "Jumlah" field if needed, then click "Masukkan Keranjang".
   - If you are not logged in (session missing `id_user`) you'll get a prompt to login. Add a session for a user or login flow if you have it.
      - If you are not logged in you'll be prompted to login — use the newly added `login.php` or create a new account with `register.php`.
   - If logged in, a JSON response and alert should say the item was added to the DB table `keranjang`.
6) Confirm by checking the database: SELECT * FROM keranjang WHERE id_user = <your_id_user> AND id_barang = <id>;

Troubleshooting
- If add-to-cart returns 500, check Apache/PHP error log and ensure DB server is available.
- If images don't show, verify paths: `assets/img/` or `image/` and filenames exist.

Additional manual tests for login/register flow
1) Open `register.php`, create a new account and ensure after registration you are redirected and the session is set (you should see your name in the nav and cart count).
2) Logout using `logout.php` and confirm session cleared and nav shows Login/Daftar links again.
3) After login, add an item from product detail and verify the item appears in the `keranjang` table for your `id_user`.

New features to test
1) Real-time cart counter
   - From product detail page, add item to cart. The navbar counter (top-right) should update immediately.

2) Update quantity in cart
   - Open `cart.php`, change the quantity number for an item. The line subtotal and the total should update automatically.
   - Setting quantity to 0 removes the item (or click Hapus to remove).

3) Checkout with stock validation
   - Open `cart.php` and click "Lanjutkan Pembayaran" which opens `checkout.php`.
   - Choose shipping and payment method and submit. If stock is insufficient the checkout will show an error.
   - On success you'll be redirected to `checkout_success.php?id=<order_id>` and the `keranjang` table will be cleared for your user.

4) Admin upload / image resizing
   - In admin > Edit Produk, upload a large image (JPEG/PNG/WEBP) for a product; the server will validate and resize it into `assets/img/`.
   - Public product pages (`products.php`, `productdetail.php`) will now show the updated images (resolver will try `assets/img/` first then `image/`).

Next improvements (optional)
- Add visual cart counter in nav and update it after adding an item.
- Provide a user-facing login/registration page so anonymous users can be asked to sign in.
- Add removal and quantity update endpoints for cart management.
