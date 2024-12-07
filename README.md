### README.md

# Audit Log Application

Audit Log Application adalah sebuah aplikasi berbasis web yang bertujuan untuk mencatat setiap aktivitas pengguna pada sistem, termasuk akses halaman, login, logout, dan aktivitas lainnya. Log tersebut disimpan dalam database dan dapat dikirim ke platform eksternal seperti WhatsApp, Telegram, dan email secara otomatis.

---

## **Alur Kerja Aplikasi**

1. **Registrasi Pengguna**: Pengguna dapat membuat akun dengan memasukkan username dan password yang terenkripsi.
2. **Login Pengguna**: Pengguna yang sudah terdaftar dapat melakukan login untuk mengakses dashboard.
3. **Pencatatan Aktivitas (Audit Log)**: Setiap aksi yang dilakukan oleh pengguna dicatat dalam sistem dengan detail seperti IP, waktu, dan aksi yang dilakukan.
4. **Notifikasi Otomatis**: Log aktivitas terbaru akan dikirimkan secara otomatis ke WhatsApp, Telegram, dan Email sesuai dengan konfigurasi yang telah diatur.

---

## **Troubleshooting**

- **Tidak bisa mengirim pesan WhatsApp**:
  - Pastikan API WhatsApp lokal berjalan di port yang benar (`http://localhost:3000`).
  - Periksa apakah firewall atau antivirus memblokir koneksi ke port tersebut.
- **Error SMTP Email**:

  - Pastikan kredensial email di `send_notifications.php` sudah benar.
  - Periksa koneksi internet dan pastikan server SMTP (Zoho) tidak sedang down.

- **Tidak ada notifikasi yang dikirim**:
  - Periksa apakah script `send_notifications.php` dijalankan secara otomatis melalui cron job atau dijalankan secara manual.
  - Pastikan bot Telegram dan API WhatsApp dikonfigurasi dengan benar.

---

## **Teknologi yang Digunakan**

- **PHP**: Untuk pemrograman sisi server.
- **MySQL/MariaDB**: Untuk penyimpanan data dan log.
- **PHPMailer**: Untuk pengiriman email melalui SMTP.
- **Telegram Bot API**: Untuk mengirimkan log audit melalui Telegram.
- **WhatsApp API**: Untuk mengirimkan log audit melalui WhatsApp.
- **Composer**: Untuk mengelola dependensi PHP.
- **Cron Job**: Untuk menjalankan script pengiriman notifikasi secara otomatis

---

## **Fitur**

1. **Registrasi Pengguna**

   - Pengguna dapat mendaftar menggunakan username dan password.

2. **Login dan Logout**

   - Autentikasi pengguna menggunakan password terenkripsi (bcrypt).

3. **Pencatatan Aktivitas (Audit Log)**

   - Setiap aktivitas pengguna dicatat dalam tabel `audit_logs` dengan detail:
     - IP Address
     - User ID
     - Aksi
     - Metode HTTP
     - URI
     - Data Request
     - Timestamp

4. **Pengiriman Notifikasi**

   - Log terkini dapat dikirimkan melalui:
     - **WhatsApp**: Menggunakan endpoint lokal.
     - **Telegram**: Menggunakan Telegram Bot API.
     - **Email**: Menggunakan SMTP (Zoho).

5. **Dashboard**
   - Mengakses daftar log audit dalam tabel yang terurut berdasarkan waktu.

---

## **Struktur Direktori**

```
project/
├── db.php                 # Konfigurasi koneksi database
├── register.php           # Halaman registrasi pengguna
├── index.php              # Halaman login pengguna
├── dashboard.php          # Halaman dashboard setelah login
├── audit_log.php          # Halaman untuk melihat log audit
├── send_notifications.php # Script untuk mengirimkan notifikasi log
├── logout.php             # Halaman logout
├── log.php                # Helper untuk mencatat log audit
├── README.md              # Dokumentasi proyek
```

---

## **Instalasi**

### **1. Prasyarat**

- PHP >= 7.4
- MySQL/MariaDB
- Composer
- Web Server (contoh: Apache, Nginx)
- Git

### **2. Clone Repository**

Clone repository ini ke dalam direktori server Anda:

```bash
git clone https://github.com/ahr-ahr/audit-log-app.git
cd audit-log-app
```

### **3. Instalasi Dependency**

Gunakan Composer untuk menginstal dependensi:

```bash
composer install
```

### **4. Konfigurasi Database**

Edit file `db.php` untuk menyesuaikan konfigurasi database:

```php
$host = 'localhost';          // Host database
$user = 'root';               // Username database
$password = '';               // Password database
$dbname = 'audit_log_app';    // Nama database
```

Buat database dan tabel:

```sql
CREATE DATABASE audit_log_app;

USE audit_log_app;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(255) NOT NULL,
    user_id INT NULL,
    action VARCHAR(255) NOT NULL,
    method VARCHAR(10) NOT NULL,
    request_uri TEXT NOT NULL,
    request_data TEXT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### **5. Jalankan Aplikasi**

- Letakkan aplikasi ini di root direktori web server (contoh: `htdocs` untuk XAMPP).
- Akses aplikasi melalui browser: `http://localhost/audit-log-app/`.

---

## **Penggunaan**

### **1. Registrasi**

- Akses `http://localhost/audit-log-app/register.php`.
- Isi form registrasi untuk membuat akun baru.

### **2. Login**

- Login menggunakan username dan password di `http://localhost/audit-log-app/index.php`.

### **3. Dashboard**

- Setelah login, akses dashboard untuk melihat daftar log audit dan mengelola aktivitas.

### **4. Notifikasi Otomatis**

- Log terkini akan dikirim melalui WhatsApp, Telegram, dan email melalui `send_notifications.php`.
- Jalankan script ini secara manual atau gunakan cron job untuk otomatisasi.

---

## **Pengiriman Notifikasi**

### **1. Konfigurasi WhatsApp**

Endpoint untuk mengirim pesan WhatsApp adalah `http://localhost:3000/api/whatsapp`. Anda harus memastikan API WhatsApp lokal berjalan dengan benar.

Untuk mengganti penerima pesan WhatsApp, ubah variabel `$recipientWhatsApp` di `send_notifications.php`:

```php
$recipientWhatsApp = '6281234567890'; // Ganti dengan nomor WhatsApp penerima
```

---

### **2. Konfigurasi Telegram**

Set token dan chat ID di `send_notifications.php` untuk mengatur bot Telegram:

```php
define('TELEGRAM_BOT_TOKEN', 'Your_Telegram_Bot_Token'); // Ganti dengan Token Bot
define('TELEGRAM_CHAT_ID', 'Your_Telegram_Chat_ID'); // Ganti dengan Chat ID penerima
```

Untuk mendapatkan Chat ID, gunakan bot Telegram:

1. Kirim pesan ke bot Anda.
2. Akses URL berikut di browser:
   ```
   https://api.telegram.org/bot<Your_Telegram_Bot_Token>/getUpdates
   ```
3. Catat nilai `chat.id` dari respons JSON.

---

### **3. Konfigurasi Email**

Edit pengaturan SMTP di `send_notifications.php` untuk mengatur server email:

```php
$mail->Host = 'smtp.zoho.com';
$mail->Username = 'your-email@example.com';   // Ganti dengan alamat email pengirim
$mail->Password = 'your-email-password';     // Ganti dengan password email pengirim
```

Untuk mengganti alamat email penerima, ubah parameter berikut:

```php
$recipientEmail = 'recipient@example.com';   // Ganti dengan alamat email penerima
sendEmail($formattedLogs, $recipientEmail);
```

---

### **Tips**

- Pastikan pengaturan di atas sesuai dengan tujuan Anda.
- Jika penerima dinamis, gunakan variabel dari database atau form input untuk mengganti nilai penerima sesuai kebutuhan.

---

## **Otomatisasi**

Gunakan **cron job** atau **task scheduler** untuk menjalankan `send_notifications.php` secara otomatis setiap beberapa menit:

```bash
*/5 * * * * php /path/to/send_notifications.php
```

---

## **Keamanan**

- Gunakan HTTPS untuk melindungi data sensitif seperti password dan token.
- Ganti kredensial default (email, database) sebelum deployment.
- Hindari menyimpan password dalam bentuk teks biasa.

---

## **Lisensi**

Proyek ini dilisensikan di bawah [MIT License](https://opensource.org/licenses/MIT). Silakan gunakan dan modifikasi sesuai kebutuhan Anda.

---

Jika Anda memiliki pertanyaan atau masalah, silakan hubungi:

- **Email**: ahr2396@gmail.com
- **Whatsapp**: +62-823-3142-2421
