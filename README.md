# MAPIA (Monitoring & Automation of Pepaya California) 🌿

[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-Neon-4169E1?style=for-the-badge&logo=postgresql)](https://neon.tech)
[![IoT](https://img.shields.io/badge/IoT-ESP32-000000?style=for-the-badge&logo=espressif)](https://www.espressif.com/)


jangan lupa coonection poolingnya dinayalakn 

**MAPIA** adalah platform berbasis Internet of Things (IoT) yang dirancang khusus untuk memantau dan mengotomatisasi perawatan bibit Pepaya California. Sistem ini mengintegrasikan sensor tanah, mikrokontroler ESP32, dan dashboard web modern untuk memastikan pertumbuhan tanaman yang optimal melalui manajemen data yang presisi.

---

## 🚀 Fitur Utama

- **Real-time Monitoring:** Pantau kelembapan tanah dan pH tanah secara langsung.
- **Smart Irrigation:** Otomatisasi penyiraman berdasarkan parameter yang ditentukan pengguna.
- **Data Analytics:** Riwayat data sensor yang tersimpan aman di cloud untuk analisis pertumbuhan.
- **Notification System:** Alert otomatis untuk kondisi kritis pada tanaman.
- **User Management:** Akses dashboard yang aman untuk pemilik lahan.

---

## 🛠️ Stack Teknologi

- **Backend:** Laravel 11 (PHP 8.3)
- **Database:** PostgreSQL (Hosted on Neon.tech)
- **Frontend:** Tailwind CSS & Blade Templating
- **Hardware:** ESP32, Soil Moisture Sensor, pH Sensor, Relay Module.

---

## 📋 Struktur Database (Migrations)

Project ini telah mengimplementasikan skema database relasional yang mencakup:
* `users` - Autentikasi pemilik sistem.
* `sensors` - Manajemen perangkat hardware.
* `parameter_penyiramans` - Konfigurasi ambang batas (Threshold) otomatisasi.
* `riwayat_sensors` - Log data sensor berkala.
* `riwayat_penyiramans` - Log aktivitas pompa air.
* `notifikasis` & `jenis_notifs` - Sistem alert terpusat.

---

## ⚙️ Instalasi

1. **Clone Repository**
   ```bash
   git clone [https://github.com/HiddenCanvas/MAPIA.git](https://github.com/HiddenCanvas/MAPIA.git)
   cd MAPIA
