# BATIM GADAI - Sistem Informasi Gadai Elektronik

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-v4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" />
  <img src="https://img.shields.io/badge/Alpine.js-3.x-77C1D2?style=for-the-badge&logo=alpinedotjs&logoColor=white" />
  <img src="https://img.shields.io/badge/Vite-5.x-646CFF?style=for-the-badge&logo=vite&logoColor=white" />
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" />
</p>

<p align="center">
  Sistem informasi berbasis web untuk pengelolaan operasional pegadaian swasta secara terkomputerisasi.<br>
  Dikembangkan untuk <strong>PT Bintang Timur</strong> sebagai bagian dari platform digital <strong>BATIM GADAI</strong>.
</p>

---

## 🏦 Tentang Proyek

<p align="justify">
<strong>BATIM GADAI</strong> adalah sistem informasi gadai elektronik yang dirancang untuk mendigitalisasi seluruh alur operasional pegadaian swasta, mulai dari pencatatan pengajuan gadai, taksiran barang, pengelolaan data nasabah, perpanjangan, hingga pelunasan gadai. Sistem ini hadir untuk menggantikan proses manual yang rentan terhadap kesalahan pencatatan dan keterlambatan informasi, sehingga seluruh operasional dapat berjalan lebih cepat, akurat, dan terpantau secara real-time.
</p>

Platform digital BATIM GADAI terdiri dari dua komponen utama:

| Platform   | Repo                        | Pengguna                   |
| ---------- | --------------------------- | -------------------------- |
| Web System | _(repo ini)_                | Superadmin, Admin, Officer |
| Mobile App | _(repo terpisah - Flutter)_ | Nasabah                    |

---

## ✨ Keunggulan Sistem

**🏷️ Loker Barcode Terintegrasi**

<p align="justify">Setiap barang jaminan disimpan di loker khusus dan diberi label barcode unik. Satu kali scan, seluruh informasi barang, pemilik, detail transaksi, dan status gadai langsung teridentifikasi.</p>

**⚡ Pencairan Hari Ini**

<p align="justify">Proses pengajuan gadai dirancang efisien sehingga nasabah dapat menerima dana di hari yang sama tanpa proses berbelit.</p>

**📊 Taksiran Transparan**

<p align="justify">Nilai taksiran dihitung langsung di depan nasabah dengan acuan yang terstandarisasi, membangun kepercayaan dan menghindari sengketa nilai.</p>

---

## 🛠️ Teknologi yang Dipakai

| Layer              | Teknologi         |
| ------------------ | ----------------- |
| Backend Framework  | Laravel 12        |
| Frontend Styling   | Tailwind CSS v4   |
| UI Interactivity   | Alpine.js         |
| Build Tool         | Vite              |
| Database           | MySQL 8.0         |
| Admin Template     | TailAdmin Laravel |
| Development Server | Laragon           |

---

## 🔐 Role Pengguna

| Role         | Platform   | Akses                                                                                 |
| ------------ | ---------- | ------------------------------------------------------------------------------------- |
| `superadmin` | Web        | Kepala outlet / direktur — akses penuh seluruh sistem dan semua cabang                |
| `admin`      | Web        | Pimpinan cabang - mengelola data dan transaksi cabang masing-masing                   |
| `officer`    | Web        | Petugas front office, kasir, dan juru taksir                                          |
| `nasabah`    | Mobile App | Pembayaran perpanjangan / pelunasan, booking kunjungan, riwayat transaksi via Flutter |

---

## 📋 Fitur Utama

### Landing Page

- Hero slider 3 slide dengan animasi transisi dan navigasi dots
- Statistik perusahaan dengan animasi counter
- Profil perusahaan dan layanan gadai yang diterima
- Alur proses gadai step-by-step yang interaktif
- Highlight keunggulan sistem (loker barcode, pencairan cepat, taksiran transparan)
- Preview aplikasi mobile dengan mockup slider
- Informasi lokasi cabang
- Syarat & ketentuan gadai
- FAQ accordion
- Formulir kontak
- Animasi scroll fade-up dan grid pattern background khas BATIM GADAI

### Backend - Admin Panel

- Dashboard statistik dengan grafik transaksi dan pendapatan
- Manajemen data nasabah
- Manajemen transaksi gadai (pengajuan, perpanjangan, pelunasan)
- Sistem loker barcode untuk identifikasi barang jaminan
- Manajemen officer dan cabang
- Role-based access control (Superadmin / Admin / Officer)
- Dark mode support

---

## 👨‍💻 Tim Pengembang

Dikembangkan oleh mahasiswa **D4 Teknik Informatika, Politeknik Negeri Jember** angkatan 2024 - Kelompok 1 Golongan C.

| Peran   | Nama                        | NIM       | GitHub                                                         |
| ------- | --------------------------- | --------- | -------------------------------------------------------------- |
| Ketua   | Yohanes Fabian S            | E41241212 | [@yhfabian](https://github.com/yhfabian)                       |
| Anggota | Faiq Raihan Albaihaqi       | E41241011 | [@faiqraihanalbaihaqi](https://github.com/faiqraihanalbaihaqi) |
| Anggota | Abdillah Aziz Putra Susan   | E41241208 | [@azizaan](https://github.com/azizaan)                         |
| Anggota | Alviansyah Nurhidayat Yahya | E41241155 | [@alviansyahny](https://github.com/alviansyahny)               |
| Anggota | Juliana Intan Purwaningtyas | E41241036 | [@tintuntan06-dotcom](https://github.com/tintuntan06-dotcom)   |

---

## 📄 Lisensi

Proyek ini dikembangkan untuk memenuhi tugas akhir semester akademik dan membantu operasional **PT Bintang Timur**. Seluruh hak cipta dilindungi.

---

<p align="center">Dikembangkan dengan ❤️ oleh Tim Kelompok 1 Golongan C &nbsp;·&nbsp; Politeknik Negeri Jember 2026</p>
