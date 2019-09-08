<?php
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('view:cache');
    return 'DONE'; //Return anything
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('verifikasi-email/{token}','Auth\RegisterController@signupActivate');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');

Route::prefix('laporan')->group(function () {
	Route::get('/', 'LaporanController@index')->name('laporan.index');
});

Route::prefix('menu')->group(function () {
	Route::get('/', 'DaftarMenuController@index')->name('daftar-menu.index');
	Route::get('kategori', 'KategoriMenuController@index')->name('kategori-menu.index');
	Route::get('item-tambahan', 'ItemTambahanController@index')->name('item-tambahan.index');
	Route::get('promo', 'PromoController@index')->name('promo.index');
	Route::get('diskon', 'DiskonController@index')->name('diskon.index');
	Route::get('pajak', 'PajakController@index')->name('pajak.index');
	Route::get('biaya-tambahan', 'BiayaTambahanController@index')->name('biaya-tambahan.index');
	Route::get('tipe-penjualan', 'TipePenjualanController@index')->name('tipe-penjualan.index');
});

Route::prefix('bahan-dapur')->group(function () {
	Route::get('/', 'BahanDapurController@index')->name('bahan-dapur.index');
	Route::get('kategori', 'KategoriBahanDapurController@index')->name('kategori-bahan-dapur.index');
	Route::get('resep', 'ResepController@index')->name('resep.index');
});

Route::prefix('perlengkapan')->group(function () {
	Route::get('/', 'DaftarPerlengkapanController@index')->name('daftar-perlengkapan.index');
	Route::get('kategori', 'KategoriPerlengkapanController@index')->name('kategori-perlengkapan.index');
});

Route::prefix('inventori')->group(function () {
	Route::get('ringkasan', 'RingkasanInventoriController@index')->name('ringkasan.index');
	Route::get('supplier', 'SupplierController@index')->name('supplier.index');
	Route::get('pesanan-pembelian', 'PesananPembelianController@index')->name('pesanan-pembelian.index');
	Route::get('penyesuaian-stok', 'PenyesuaianStokController@index')->name('penyesuaian-stok.index');
	Route::get('transfer', 'TransferController@index')->name('transfer.index');
});

Route::prefix('pelanggan')->group(function () {
	Route::get('feedback', 'FeedbackController@index')->name('feedback.index');
	Route::get('berlangganan', 'BerlanggananController@index')->name('berlangganan.index');
});

Route::prefix('karyawan')->group(function () {
	Route::get('/', 'DaftarKaryawanController@index')->name('daftar-karyawan.index');
	Route::get('jenis', 'JenisKaryawanController@index')->name('jenis-karyawan.index');
});

Route::prefix('meja')->group(function () {
	Route::get('/', 'PosisiMejaController@index')->name('posisi-meja.index');
	Route::get('kategori', 'KategoriMejaController@index')->name('kategori-meja.index');
});

Route::get('perangkat', 'PerangkatController@index')->name('perangkat.index');

Route::prefix('pengaturan')->group(function () {
	Route::get('akun', 'PengaturanAkunController@index')->name('akun.index');
	Route::get('billing', 'BillingController@index')->name('billing.index');
	Route::get('outlet', 'OutletController@index')->name('outlet.index');
	Route::get('akun-bank', 'AkunBankController@index')->name('akun-bank.index');
	Route::get('informasi-bisnis', 'InformasiBisnisController@index')->name('informasi-bisnis.index');
	Route::get('metode-pembayaran', 'MetodePembayaranController@index')->name('metode-pembayaran.index');
	Route::get('receipt', 'ReceiptController@index')->name('receipt.index');
	Route::get('pengaturan-aplikasi', 'PengaturanAplikasiController@index')->name('pengaturan-aplikasi.index');
});

Route::get('notifikasi', 'NotifikasiController@index')->name('notifikasi.index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
