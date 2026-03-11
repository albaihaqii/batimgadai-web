# BATIM GADAI — Sistem Informasi Gadai Elektronik

> Sistem Informasi berbasis web untuk pengelolaan transaksi gadai barang elektronik dan barang bergerak secara terkomputerisasi, dikembangkan untuk **PT Bintang Timur**.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpinedotjs&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

---

## 📖 Tentang Proyek

**BATIM GADAI** adalah sistem informasi gadai elektronik yang dirancang untuk membantu pengelolaan operasional pegadaian secara digital. Sistem ini membantu pengelolaan operasional pegadaian swasta dan menggantikan proses manual dalam pencatatan transaksi gadai, pengelolaan data nasabah, taksiran barang, perpanjangan, hingga pelunasan gadai.

Sistem ini merupakan bagian dari platform digital BATIM GADAI yang terdiri dari:

- **Web System** _(repo ini)_ — untuk superadmin, admin, dan officer
- **Mobile App** _(repo terpisah)_ — untuk nasabah berbasis Flutter

### Keunggulan Sistem

- 🏷️ **Loker Barcode** — Barang jaminan disimpan di loker khusus dengan sistem barcode terintegrasi. Setiap barang dapat diidentifikasi pemilik dan detailnya hanya dengan scan barcode.
- ⚡ **Pencairan Hari Ini** — Proses gadai cepat dengan dana cair di hari yang sama saat pengajuan.
- 📊 **Taksiran Transparan** — Nilai taksiran dihitung langsung di depan nasabah.

---

## 🧱 Teknologi

| Layer                  | Teknologi         |
| ---------------------- | ----------------- |
| Backend Framework      | Laravel 12        |
| Frontend Styling       | Tailwind CSS v4   |
| Frontend Interactivity | Alpine.js         |
| Build Tool             | Vite              |
| Database               | MySQL             |
| Admin Template         | TailAdmin Laravel |
| Server Lokal           | Laragon           |

---

## 👥 Role Pengguna

| Role         | Akses      | Deskripsi                                             |
| ------------ | ---------- | ----------------------------------------------------- |
| `superadmin` | Web        | Kepala outlet / direktur — akses penuh seluruh sistem |
| `admin`      | Web        | Pimpinan cabang — kelola data cabang masing-masing    |
| `officer`    | Web        | Petugas front office, kasir, juru taksir              |
| `nasabah`    | Mobile App | Akses via aplikasi Flutter _(repo terpisah)_          |

---

## 🗂️ Fitur Utama

### Landing Page

- Hero slider 3 slide dengan animasi fade
- Profil perusahaan dan layanan gadai
- Alur proses gadai step-by-step
- Preview aplikasi mobile dengan mockup slider
- Lokasi cabang
- Syarat & ketentuan gadai
- FAQ
- Formulir kontak
- Animasi scroll fade-up dan grid pattern background

### Backend (Admin Panel)

- Dashboard statistik dengan grafik
- Manajemen data nasabah
- Manajemen transaksi gadai (pengajuan, perpanjangan, pelunasan)
- Manajemen officer dan cabang
- Sistem loker barcode barang jaminan
- Role-based access control (Superadmin / Admin / Officer)
- Dark mode support

---

## 👨‍💻 Tim Pengembang

Proyek ini dikembangkan oleh mahasiswa **Teknik Informatika, Politeknik Negeri Jember** angkatan 2024.

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

> Dikembangkan dengan ❤️ oleh Tim Kelompok 1 Golongan C — Politeknik Negeri Jember 2026
