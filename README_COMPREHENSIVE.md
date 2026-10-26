# 🏥 GO HEALTY - SISTEM MANAJEMEN APOTEK ONLINE

**Sistem apotek online dengan panel admin dan panel customer yang terpisah sepenuhnya untuk keamanan dan kemudahan penggunaan maksimal.**

---

## 📑 TABLE OF CONTENTS

1. [Panduan Cepat](#panduan-cepat)
2. [Fitur Utama](#fitur-utama)
3. [Dashboard Pelanggan](#dashboard-pelanggan)
4. [Dashboard Admin](#dashboard-admin)
5. [Sistem Keamanan](#sistem-keamanan)
6. [Daftar URL Lengkap](#daftar-url-lengkap)
7. [Panduan Penggunaan](#panduan-penggunaan)
8. [Teknologi & Stack](#teknologi--stack)
9. [Struktur Database](#struktur-database)
10. [Log Perbaikan Bug](#log-perbaikan-bug)
11. [Skenario Testing](#skenario-testing)
12. [Catatan Penting](#catatan-penting)

---

## 🚀 PANDUAN CEPAT

### Langkah 1: Akses Halaman Informasi

Buka halaman informasi untuk melihat panduan visual:

```
http://localhost/Apotek_Kelompok_3/akses.php
```

### Langkah 2: Pilih Login Gateway

Akses halaman pilihan login:

```
http://localhost/Apotek_Kelompok_3/pages/login-gateway.php
```

### Langkah 3: Login Sesuai Role

- **Sebagai Customer**: Pilih "Login Pelanggan"
- **Sebagai Admin**: Pilih "Login Admin"

---

## ✨ FITUR UTAMA

### 🏪 Untuk Pelanggan

- ✅ Belanja obat online dengan kategori lengkap
- ✅ Kelola keranjang belanja
- ✅ Lihat riwayat pesanan dan detail pesanan
- ✅ Batalkan pesanan (status pending/processing)
- ✅ Monitor status refund otomatis
- ✅ Kelola profil account
- ✅ Upload gambar produk dengan preview realtime

### 🔐 Untuk Admin

- ✅ Kelola data obat (Tambah/Edit/Hapus)
- ✅ Upload gambar obat dengan preview realtime
- ✅ Kelola kategori dan supplier
- ✅ Kelola semua pesanan pelanggan
- ✅ Update status pesanan
- ✅ Monitor refund otomatis
- ✅ Kelola data pelanggan
- ✅ Lihat statistik pembayaran
- ✅ Dashboard dengan overview lengkap

---

## 👥 DASHBOARD PELANGGAN

### Menu Utama Pelanggan

- 🏠 **Home** - Halaman utama
- 💊 **Produk** - Browse dan cari obat
- 🛒 **Keranjang** - Kelola keranjang belanja
- 📦 **Pesanan** - Riwayat pesanan
- 👤 **Profil** - Data account

### Fitur Pelanggan

#### 1. Belanja Obat

- Browse produk dari katalog lengkap
- Cari berdasarkan nama atau kategori
- Lihat detail produk dengan gambar
- Tambah ke keranjang belanja

#### 2. Keranjang Belanja

- Lihat semua item di keranjang
- Ubah jumlah item
- Hapus item dari keranjang
- Lanjut ke checkout

#### 3. Checkout & Pesanan

- Isi data pengiriman
- Pilih metode pembayaran:
  - Transfer Bank
  - Kartu Kredit
  - E-Wallet
  - Bayar di Tempat (Cash)
- Konfirmasi pesanan

#### 4. Pembatalan Pesanan

- Batalkan pesanan dengan status **pending** atau **processing**
- Konfirmasi sebelum pembatalan
- Refund otomatis untuk metode pembayaran digital

#### 5. Refund Otomatis

- Triggered saat pesanan dibatalkan
- Untuk metode: Transfer Bank, Kartu Kredit, E-Wallet
- Status pembayaran otomatis berubah → **Refund**
- Bayar di Tempat (Cash) tidak memicu refund
- Customer bisa monitor status refund

#### 6. Profil Account

- Lihat data account
- Edit profil
- Kelola password
- Lihat history pesanan

### URL Akses Pelanggan

| Menu      | URL                                                           |
| --------- | ------------------------------------------------------------- |
| Home      | `http://localhost/Apotek_Kelompok_3/`                         |
| Produk    | `http://localhost/Apotek_Kelompok_3/pages/products.php`       |
| Keranjang | `http://localhost/Apotek_Kelompok_3/pages/cart.php`           |
| Pesanan   | `http://localhost/Apotek_Kelompok_3/pages/orders.php`         |
| Profil    | `http://localhost/Apotek_Kelompok_3/pages/profile.php`        |
| Login     | `http://localhost/Apotek_Kelompok_3/pages/login-customer.php` |
| Register  | `http://localhost/Apotek_Kelompok_3/pages/register.php`       |
| Checkout  | `http://localhost/Apotek_Kelompok_3/pages/checkout.php`       |

---

## 🔐 DASHBOARD ADMIN

### Menu Utama Admin

- 📊 **Dashboard** - Overview statistik
- 💊 **Kelola Obat** - CRUD obat dengan upload gambar
- 📂 **Kategori** - CRUD kategori
- 🚚 **Supplier** - CRUD supplier dan kelola produk supplier
- 📦 **Pesanan** - Monitor pesanan pelanggan
- 💳 **Pembayaran** - Lihat status pembayaran
- 👥 **Pengguna** - Kelola customer

### Fitur Admin

#### 1. Dashboard

- Overview statistik: Total pesanan, revenue, customer, produk
- Grafik penjualan
- Recent orders list
- Quick access ke semua menu

#### 2. Kelola Obat

- **Daftar Obat**: View semua obat dengan status
- **Tambah Obat**:
  - Input nama, kategori, harga, stok
  - Input komposisi, kemasan, manufacturer
  - Upload gambar obat
  - Preview gambar real-time
- **Edit Obat**: Update informasi obat
- **Hapus Obat**: Delete obat dari sistem

#### 3. Kelola Kategori

- Daftar kategori obat
- Tambah kategori baru
- Edit kategori
- Hapus kategori

#### 4. Kelola Supplier

- Daftar supplier
- Tambah supplier baru
- Edit supplier
- Hapus supplier
- **Kelola Produk Supplier**: Manage supplier products

#### 5. Kelola Pesanan

- Daftar semua pesanan dengan status
- Filter berdasarkan status
- **Detail Pesanan**:
  - Lihat informasi customer
  - Lihat item pesanan
  - Update status pesanan (pending → processing → shipped → delivered)
  - Update status pembayaran
  - Monitor refund status

#### 6. Kelola Pembayaran

- Daftar semua pembayaran
- Status pembayaran (pending, completed, refund)
- Detail pembayaran per pesanan

#### 7. Kelola Pengguna

- Daftar semua customer
- Lihat detail customer
- Edit data customer
- Hapus customer

### URL Akses Admin

| Menu              | URL                                                                  |
| ----------------- | -------------------------------------------------------------------- |
| Dashboard         | `http://localhost/Apotek_Kelompok_3/admin/`                          |
| Kelola Obat       | `http://localhost/Apotek_Kelompok_3/admin/medicines.php`             |
| Tambah Obat       | `http://localhost/Apotek_Kelompok_3/admin/add-medicine.php`          |
| Edit Obat         | `http://localhost/Apotek_Kelompok_3/admin/edit-medicine.php?id=[ID]` |
| Kelola Kategori   | `http://localhost/Apotek_Kelompok_3/admin/categories.php`            |
| Kelola Supplier   | `http://localhost/Apotek_Kelompok_3/admin/suppliers.php`             |
| Tambah Supplier   | `http://localhost/Apotek_Kelompok_3/admin/add-supplier.php`          |
| Edit Supplier     | `http://localhost/Apotek_Kelompok_3/admin/edit-supplier.php?id=[ID]` |
| Kelola Pesanan    | `http://localhost/Apotek_Kelompok_3/admin/orders.php`                |
| Detail Pesanan    | `http://localhost/Apotek_Kelompok_3/admin/order-detail.php?id=[ID]`  |
| Kelola Pembayaran | `http://localhost/Apotek_Kelompok_3/admin/payments.php`              |
| Kelola Pengguna   | `http://localhost/Apotek_Kelompok_3/admin/users.php`                 |
| Login Admin       | `http://localhost/Apotek_Kelompok_3/pages/login-admin.php`           |

---

## 🔒 SISTEM KEAMANAN

### 1. Role-Based Access Control (RBAC)

Sistem membedakan 2 role utama:

- **CUSTOMER** - Pelanggan yang berbelanja
- **ADMIN** - Administrator sistem

Setiap role memiliki:

- Halaman akses berbeda
- Data yang dapat diakses berbeda
- Permission yang berbeda

### 2. User Ownership Validation

Setiap customer hanya bisa akses data milik mereka sendiri:

- Customer hanya bisa lihat pesanan milik mereka
- Customer hanya bisa lihat cart milik mereka
- Customer hanya bisa edit profile milik mereka

### 3. No Cross-Access

- Admin tidak bisa akses cart atau order detail sebagai customer
- Admin hanya bisa lihat data pesanan untuk monitoring
- Customer tidak bisa akses admin panel

### 4. Login Terpisah

- Customer dan admin memiliki login terpisah
- Validasi role di setiap halaman
- Session-based authentication

### 5. Proteksi Halaman

```php
// Cek apakah sudah login
requireLogin();

// Cek apakah user adalah admin
requireAdmin();
```

### 6. Session Management

- Setiap login menyimpan: `user_id`, `username`, `email`, `role`
- Logout menghapus semua session data
- Redirect otomatis jika belum login

---

## 📍 DAFTAR URL LENGKAP

### 🏠 Halaman Utama

| Menu            | URL                                                          |
| --------------- | ------------------------------------------------------------ |
| Informasi Akses | `http://localhost/Apotek_Kelompok_3/akses.php`               |
| Login Gateway   | `http://localhost/Apotek_Kelompok_3/pages/login-gateway.php` |
| Home            | `http://localhost/Apotek_Kelompok_3/`                        |

### 👥 Akses Pelanggan

| Menu            | URL                                                                   |
| --------------- | --------------------------------------------------------------------- |
| Login Pelanggan | `http://localhost/Apotek_Kelompok_3/pages/login-customer.php`         |
| Register        | `http://localhost/Apotek_Kelompok_3/pages/register.php`               |
| Produk          | `http://localhost/Apotek_Kelompok_3/pages/products.php`               |
| Detail Produk   | `http://localhost/Apotek_Kelompok_3/pages/product-detail.php?id=[ID]` |
| Keranjang       | `http://localhost/Apotek_Kelompok_3/pages/cart.php`                   |
| Checkout        | `http://localhost/Apotek_Kelompok_3/pages/checkout.php`               |
| Pesanan         | `http://localhost/Apotek_Kelompok_3/pages/orders.php`                 |
| Detail Pesanan  | `http://localhost/Apotek_Kelompok_3/pages/order-detail.php?id=[ID]`   |
| Profil          | `http://localhost/Apotek_Kelompok_3/pages/profile.php`                |
| Logout          | `http://localhost/Apotek_Kelompok_3/pages/logout.php`                 |

### 🔐 Akses Admin

| Menu            | URL                                                                  |
| --------------- | -------------------------------------------------------------------- |
| Login Admin     | `http://localhost/Apotek_Kelompok_3/pages/login-admin.php`           |
| Dashboard       | `http://localhost/Apotek_Kelompok_3/admin/`                          |
| Kelola Obat     | `http://localhost/Apotek_Kelompok_3/admin/medicines.php`             |
| Tambah Obat     | `http://localhost/Apotek_Kelompok_3/admin/add-medicine.php`          |
| Edit Obat       | `http://localhost/Apotek_Kelompok_3/admin/edit-medicine.php?id=[ID]` |
| Kelola Kategori | `http://localhost/Apotek_Kelompok_3/admin/categories.php`            |
| Kelola Supplier | `http://localhost/Apotek_Kelompok_3/admin/suppliers.php`             |
| Tambah Supplier | `http://localhost/Apotek_Kelompok_3/admin/add-supplier.php`          |
| Edit Supplier   | `http://localhost/Apotek_Kelompok_3/admin/edit-supplier.php?id=[ID]` |
| Pesanan         | `http://localhost/Apotek_Kelompok_3/admin/orders.php`                |
| Detail Pesanan  | `http://localhost/Apotek_Kelompok_3/admin/order-detail.php?id=[ID]`  |
| Pembayaran      | `http://localhost/Apotek_Kelompok_3/admin/payments.php`              |
| Pengguna        | `http://localhost/Apotek_Kelompok_3/admin/users.php`                 |

---

## 📖 PANDUAN PENGGUNAAN

### Untuk Pelanggan Baru

1. Kunjungi `pages/login-gateway.php`
2. Pilih "Login Pelanggan"
3. Klik "Register" untuk akun baru
4. Isi data dan buat password
5. Login dan belanja obat
6. Lihat pesanan di "Pesanan"
7. Batalkan pesanan jika perlu

### Untuk Admin

1. Kunjungi `pages/login-gateway.php`
2. Pilih "Login Admin"
3. Login dengan akun admin
4. Akses dashboard admin
5. Kelola sistem apotek (obat, kategori, supplier, pesanan, dll)

### Untuk Beralih Akun

1. **Logout** dari akun saat ini
2. Akses `pages/login-gateway.php`
3. **Pilih role yang berbeda**
4. Login dengan akun yang sesuai

---

## 💻 TEKNOLOGI & STACK

| Teknologi    | Versi |
| ------------ | ----- |
| PHP          | 7.4+  |
| MySQL        | 5.7+  |
| Bootstrap    | 5.3.0 |
| Font Awesome | 6.4.0 |
| JavaScript   | ES6+  |
| jQuery       | 3.6.0 |

---

## 🗄️ STRUKTUR DATABASE

### Tabel Utama

1. **users** - Data pelanggan dan admin
   - user_id (PK)
   - username, email, password
   - full_name, phone, address
   - role (customer/admin)

2. **categories** - Kategori obat
   - category_id (PK)
   - category_name
   - description

3. **medicines** - Data obat
   - medicine_id (PK)
   - medicine_name, category_id
   - description, composition
   - price, stock, unit
   - image, status

4. **cart** - Keranjang belanja
   - cart_id (PK)
   - user_id, medicine_id
   - quantity, price

5. **orders** - Pesanan
   - order_id (PK)
   - user_id, total_amount
   - status (pending/processing/shipped/delivered/cancelled)
   - payment_method

6. **order_details** - Detail pesanan
   - order_detail_id (PK)
   - order_id, medicine_id
   - quantity, price

7. **payments** - Pembayaran
   - payment_id (PK)
   - order_id, amount
   - method, status (pending/completed/refund)

8. **reviews** - Ulasan produk
   - review_id (PK)
   - medicine_id, user_id
   - rating, comment

9. **stock_log** - Log perubahan stok
   - log_id (PK)
   - medicine_id, quantity_change
   - action, created_at

---

## 🐛 LOG PERBAIKAN BUG

### BUG FIX #1 - Stock Log Table Issue

**Masalah**: Saat admin mengubah status pesanan menjadi 'processing', muncul error:

```
Error: Unknown column 'order_id' in 'field list'
```

**Penyebab**: Code mencoba INSERT order_id ke tabel stock_log, padahal kolom ini tidak ada.

**Solusi**: Menghapus parameter order_id dari semua query INSERT stock_log.

**File yang Diperbaiki**:

1. `/admin/order-detail.php` - Fixed 2 INSERT queries
2. `/pages/order-detail.php` - Already correct

**Query Sebelum**:

```php
INSERT INTO stock_log (medicine_id, quantity_change, new_stock, action, order_id)
VALUES ($medicine_id, $quantity, $new_stock, 'purchase', $order_id)
```

**Query Sesudah**:

```php
INSERT INTO stock_log (medicine_id, quantity_change, new_stock, action)
VALUES ($medicine_id, $quantity, $new_stock, 'purchase')
```

**Status**: ✅ RESOLVED

---

## 🧪 SKENARIO TESTING

### TEST GROUP 1: CUSTOMER ROLE - CUSTOMER PAGES

#### TEST 1.1: Customer Login & Akses Cart

- **Steps**: Login → Buka URL `/pages/cart.php`
- **Expected**: Halaman cart ditampilkan dengan item milik customer tersebut
- **Result**: ✅ PASS

#### TEST 1.2: Customer Lihat Order Sendiri

- **Steps**: Login → Buka URL `/pages/orders.php`
- **Expected**: Daftar pesanan customer ditampilkan
- **Result**: ✅ PASS

#### TEST 1.3: Customer Lihat Detail Order

- **Steps**: Login → Orders → Klik detail pesanan
- **Expected**: Detail pesanan ditampilkan dengan data lengkap
- **Result**: ✅ PASS

### TEST GROUP 2: ADMIN ROLE - ADMIN PAGES

#### TEST 2.1: Admin Login & Akses Dashboard

- **Steps**: Login as admin → Buka URL `/admin/`
- **Expected**: Admin dashboard ditampilkan
- **Result**: ✅ PASS

#### TEST 2.2: Admin Lihat Semua Orders

- **Steps**: Admin login → Pesanan
- **Expected**: Semua pesanan dari semua customer ditampilkan
- **Result**: ✅ PASS

#### TEST 2.3: Admin Update Order Status

- **Steps**: Admin → Pesanan → Detail → Update Status → Save
- **Expected**: Status pesanan berubah, stock berkurang
- **Result**: ✅ PASS

### TEST GROUP 3: CUSTOMER PEMBATALAN

#### TEST 3.1: Customer Batalkan Pesanan (Status Pending)

- **Steps**: Customer → Orders → Batalkan pesanan status pending
- **Expected**: Pesanan dibatalkan, konfirmasi success
- **Result**: ✅ PASS

#### TEST 3.2: Customer Batalkan Pesanan (Status Processing)

- **Steps**: Customer → Orders → Batalkan pesanan status processing
- **Expected**: Pesanan dibatalkan, refund triggered
- **Result**: ✅ PASS

### TEST GROUP 4: SECURITY TESTS

#### TEST 4.1: Customer Tidak Bisa Akses Admin Panel

- **Steps**: Login as customer → Akses `/admin/`
- **Expected**: Redirect ke home page
- **Result**: ✅ PASS

#### TEST 4.2: Admin Tidak Bisa Akses Customer Cart

- **Steps**: Login as admin → Akses `/pages/cart.php`
- **Expected**: Redirect atau empty cart
- **Result**: ✅ PASS

#### TEST 4.3: Customer Tidak Bisa Lihat Pesanan Customer Lain

- **Steps**: Login as customer A → Akses `/pages/order-detail.php?id=[customer_B_order]`
- **Expected**: Redirect atau error
- **Result**: ✅ PASS

---

## 📝 CATATAN PENTING

### Setup Requirements

1. **Database**: Pastikan `db_apotek` sudah dibuat dan di-import dari `database/db_apotek.sql`
2. **Admin Account**: Buat admin user dengan role 'admin' untuk akses admin panel
3. **File Permissions**: Pastikan folder `assets/img/medicines/` bisa write
4. **XAMPP Running**: Pastikan Apache dan MySQL sudah running
5. **PHP Version**: Minimum PHP 7.4+

### Fitur Khusus

1. **Pembatalan Pesanan**: Hanya untuk status pending dan processing
2. **Refund Otomatis**: Trigger saat pesanan dibatalkan dengan metode pembayaran digital
3. **Image Upload**: Real-time preview gambar saat upload
4. **Stock Management**: Auto pengurangan stok saat pesanan processing
5. **Login Gateway**: Halaman baru untuk kemudahan memilih tipe login

### Security Tips

- Selalu logout setelah selesai menggunakan
- Jangan share password dengan siapapun
- Gunakan password yang kuat (kombinasi huruf, angka, simbol)
- Secara berkala ubah password
- Hati-hati saat mengklik link dari email tidak resmi

---

## 🎯 KEUNGGULAN SISTEM

✨ **Keamanan Maksimal**

- Login terpisah untuk customer dan admin
- Role-based access control
- Session validation di setiap halaman

✨ **User Experience Baik**

- Login gateway dengan UI menarik
- Navigation yang jelas
- Error handling yang informatif

✨ **Feature-Rich**

- Pembatalan pesanan otomatis
- Refund otomatis berdasarkan metode pembayaran
- Image preview real-time
- Order tracking lengkap

✨ **Scalable**

- Mudah menambah fitur baru
- Database structure yang baik
- Code yang rapi dan terstruktur

---

## 🔗 QUICK LINKS

- **Home**: `http://localhost/Apotek_Kelompok_3/`
- **Login Gateway**: `http://localhost/Apotek_Kelompok_3/pages/login-gateway.php`
- **Admin Panel**: `http://localhost/Apotek_Kelompok_3/admin/`
- **Info Akses**: `http://localhost/Apotek_Kelompok_3/akses.php`

---

## 📅 Informasi Proyek

- **Nama Proyek**: Go Healty
- **Jenis**: Sistem Manajemen Apotek Online
- **Status**: ✅ Production Ready
- **Created**: 2 Februari 2026
- **Last Updated**: 5 Februari 2026
- **Dokumentasi**: Lengkap dan Up-to-date

---

## 🤝 Support & Bantuan

Untuk pertanyaan atau bantuan:

1. Cek dokumentasi di README.md ini
2. Lihat panduan di akses.php
3. Coba akses login-gateway.php untuk tutorial visual
4. Periksa test scenarios untuk contoh penggunaan

---

**Selamat menggunakan Sistem Go Healty! 🎉**

Semoga sistem ini memudahkan Anda dalam mengelola apotek online. Jika ada pertanyaan atau saran, jangan ragu untuk menghubugi tim development.

Terima kasih telah menggunakan Go Healty! 💚
