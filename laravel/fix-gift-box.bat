@echo off
echo === MEMPERBAIKI MASALAH GIFT BOX ===
echo.

echo 1. Menjalankan migration jika belum...
php artisan migrate --force

echo.
echo 2. Menjalankan seeder untuk menambahkan data gift default...
php artisan db:seed --class=GiftSeeder

echo.
echo 3. Mengecek data gift yang tersedia...
php artisan tinker --execute="$gifts = App\Models\Gift::all(); echo 'Total gifts: ' . $gifts->count() . PHP_EOL; foreach($gifts as $gift) { echo '- ' . $gift->nama_hadiah . ' (ID: ' . $gift->id . ')' . PHP_EOL; }"

echo.
echo 4. Membuat folder storage untuk gambar gift jika belum ada...
if not exist "storage\app\public\gifts" mkdir storage\app\public\gifts
if not exist "public\storage" mkdir public\storage

echo.
echo 5. Membuat symbolic link untuk storage...
php artisan storage:link

echo.
echo === SELESAI ===
echo Silakan refresh halaman dan coba lagi gift box.
echo Jika masih bermasalah, pastikan:
echo - Database sudah ter-migrate dengan benar
echo - Ada data di tabel 'gifts'
echo - Storage link sudah dibuat
pause