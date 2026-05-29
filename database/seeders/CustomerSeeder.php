<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $smg = DB::table('cabang')->where('kode', 'SMG')->value('id');
        $mst = DB::table('cabang')->where('kode', 'MST')->value('id');
        $krm = DB::table('cabang')->where('kode', 'KRM')->value('id');
        $superadminId = DB::table('users')->where('role', 'superadmin')->value('id');

        $nasabah = [
            // Cabang Semanggi
            // Mahasiswa
            ['no_cif' => 'CIF-SMG-000001', 'nama' => 'Faiq Raihan Albaihaqi',     'no_ktp' => '3509011505030001', 'no_hp' => '085648912301', 'alamat' => 'Jl. Kalimantan No.37, Sumbersari, Jember',       'cabang_id' => $smg, 'tgl_bergabung' => '2026-01-03'],
            ['no_cif' => 'CIF-SMG-000002', 'nama' => 'Rizky Aditya Nugraha',      'no_ktp' => '3509011204040002', 'no_hp' => '082145671001', 'alamat' => 'Jl. Jawa No.14, Sumbersari, Jember',              'cabang_id' => $smg, 'tgl_bergabung' => '2026-01-05'],
            ['no_cif' => 'CIF-SMG-000003', 'nama' => 'Kevin Dwi Prasetyo',        'no_ktp' => '3509011506050003', 'no_hp' => '081357920001', 'alamat' => 'Jl. Sumatra No.8, Sumbersari, Jember',             'cabang_id' => $smg, 'tgl_bergabung' => '2026-01-07'],
            ['no_cif' => 'CIF-SMG-000004', 'nama' => 'Dimas Bagas Saputra',       'no_ktp' => '3509011807050004', 'no_hp' => '089654320001', 'alamat' => 'Perum Griya Indah Blok A No.5, Patrang, Jember',   'cabang_id' => $smg, 'tgl_bergabung' => '2026-01-10'],
            ['no_cif' => 'CIF-SMG-000005', 'nama' => 'Alif Wahyu Firmansyah',     'no_ktp' => '3509012009040005', 'no_hp' => '085712340001', 'alamat' => 'Jl. Veteran No.22, Sumbersari, Jember',            'cabang_id' => $smg, 'tgl_bergabung' => '2026-01-12'],
            ['no_cif' => 'CIF-SMG-000006', 'nama' => 'Gilang Ramadhan Putra',     'no_ktp' => '3509011003050006', 'no_hp' => '081298760001', 'alamat' => 'Jl. Nusantara No.5, Sumbersari, Jember',           'cabang_id' => $smg, 'tgl_bergabung' => '2026-01-15'],
            // Masyarakat umum / pegawai / wiraswasta
            ['no_cif' => 'CIF-SMG-000007', 'nama' => 'Suharto Wibowo',            'no_ktp' => '3509011203560007', 'no_hp' => '081234567801', 'alamat' => 'Jl. Srikoyo No.12, Patrang, Jember',              'cabang_id' => $smg, 'tgl_bergabung' => '2026-01-18'],
            ['no_cif' => 'CIF-SMG-000008', 'nama' => 'Bambang Eko Prasetyo',      'no_ktp' => '3509011208750008', 'no_hp' => '081357924008', 'alamat' => 'Jl. PB Sudirman No.45, Kaliwates, Jember',         'cabang_id' => $smg, 'tgl_bergabung' => '2026-01-20'],
            ['no_cif' => 'CIF-SMG-000009', 'nama' => 'Sumiati Rahayu',            'no_ktp' => '3509014504620009', 'no_hp' => '081234567009', 'alamat' => 'Jl. Nusantara No.5, Sumbersari, Jember',           'cabang_id' => $smg, 'tgl_bergabung' => '2026-01-22'],
            ['no_cif' => 'CIF-SMG-000010', 'nama' => 'Dewi Nur Cahyani',          'no_ktp' => '3509014407030010', 'no_hp' => '082145678010', 'alamat' => 'Jl. Mastrip No.8, Kaliwates, Jember',              'cabang_id' => $smg, 'tgl_bergabung' => '2026-01-25'],
            ['no_cif' => 'CIF-SMG-000011', 'nama' => 'Hendra Kurniawan',          'no_ktp' => '3509011505780011', 'no_hp' => '082198760011', 'alamat' => 'Jl. Semanggi No.7, Patrang, Jember',               'cabang_id' => $smg, 'tgl_bergabung' => '2026-02-01'],
            ['no_cif' => 'CIF-SMG-000012', 'nama' => 'Agus Setiawan Malik',       'no_ktp' => '3509011507680012', 'no_hp' => '081765430012', 'alamat' => 'Jl. Brantas No.18, Sumbersari, Jember',            'cabang_id' => $smg, 'tgl_bergabung' => '2026-02-05'],
            ['no_cif' => 'CIF-SMG-000013', 'nama' => 'Rudi Hartono',              'no_ktp' => '3509011306700013', 'no_hp' => '085634560013', 'alamat' => 'Jl. Jawa No.33, Sumbersari, Jember',               'cabang_id' => $smg, 'tgl_bergabung' => '2026-02-08'],
            ['no_cif' => 'CIF-SMG-000014', 'nama' => 'Siti Aminah Lestari',       'no_ktp' => '3509016809800014', 'no_hp' => '089654320014', 'alamat' => 'Jl. Semanggi No.3, Patrang, Jember',               'cabang_id' => $smg, 'tgl_bergabung' => '2026-02-10'],
            ['no_cif' => 'CIF-SMG-000015', 'nama' => 'Wahyu Tri Santoso',         'no_ktp' => '3509011402750015', 'no_hp' => '081234560015', 'alamat' => 'Jl. Kalimantan No.10, Sumbersari, Jember',         'cabang_id' => $smg, 'tgl_bergabung' => '2026-02-15'],
            ['no_cif' => 'CIF-SMG-000016', 'nama' => 'Nur Aini Rahmawati',        'no_ktp' => '3509015509020016', 'no_hp' => '081298760016', 'alamat' => 'Perum Griya Indah Blok C No.7, Patrang, Jember',   'cabang_id' => $smg, 'tgl_bergabung' => '2026-02-18'],
            ['no_cif' => 'CIF-SMG-000017', 'nama' => 'Budi Santoso Wicaksono',    'no_ktp' => '3509011105680017', 'no_hp' => '082345670017', 'alamat' => 'Jl. Gajahmada No.21, Kaliwates, Jember',           'cabang_id' => $smg, 'tgl_bergabung' => '2026-02-20'],
            ['no_cif' => 'CIF-SMG-000018', 'nama' => 'Eko Prasetyo Utomo',        'no_ktp' => '3509011807720018', 'no_hp' => '085698760018', 'alamat' => 'Jl. Brawijaya No.9, Kaliwates, Jember',            'cabang_id' => $smg, 'tgl_bergabung' => '2026-03-01'],
            ['no_cif' => 'CIF-SMG-000019', 'nama' => 'Arif Rahman Hakim',         'no_ktp' => '3509011904800019', 'no_hp' => '081765430019', 'alamat' => 'Jl. Mastrip No.55, Kaliwates, Jember',             'cabang_id' => $smg, 'tgl_bergabung' => '2026-03-05'],
            ['no_cif' => 'CIF-SMG-000020', 'nama' => 'Putri Handayani',           'no_ktp' => '3509016205900020', 'no_hp' => '089876540020', 'alamat' => 'Jl. Semanggi No.15, Patrang, Jember',              'cabang_id' => $smg, 'tgl_bergabung' => '2026-03-10'],

            // Cabang Mastrip
            // Mahasiswa
            ['no_cif' => 'CIF-MST-000001', 'nama' => 'Bagas Adi Nugroho',         'no_ktp' => '3509011404050021', 'no_hp' => '082145671021', 'alamat' => 'Jl. Mastrip No.34, Kebonsari, Jember',             'cabang_id' => $mst, 'tgl_bergabung' => '2026-01-04'],
            ['no_cif' => 'CIF-MST-000002', 'nama' => 'Andika Surya Pratama',      'no_ktp' => '3509011706040022', 'no_hp' => '081357920022', 'alamat' => 'Jl. Wijaya Kusuma No.9, Kaliwates, Jember',        'cabang_id' => $mst, 'tgl_bergabung' => '2026-01-08'],
            ['no_cif' => 'CIF-MST-000023', 'nama' => 'Reyhan Maulana Yusuf',      'no_ktp' => '3509012309050023', 'no_hp' => '089654320023', 'alamat' => 'Perum Mastrip Indah Blok B No.12, Jember',         'cabang_id' => $mst, 'tgl_bergabung' => '2026-01-11'],
            ['no_cif' => 'CIF-MST-000024', 'nama' => 'Hafidz Nur Rahman',         'no_ktp' => '3509012108040024', 'no_hp' => '085712340024', 'alamat' => 'Jl. Kebonsari No.7, Sumbersari, Jember',          'cabang_id' => $mst, 'tgl_bergabung' => '2026-01-14'],
            ['no_cif' => 'CIF-MST-000025', 'nama' => 'Zulfikar Ahmad Fauzi',      'no_ktp' => '3509012505050025', 'no_hp' => '081298760025', 'alamat' => 'Jl. Jawa No.19, Sumbersari, Jember',              'cabang_id' => $mst, 'tgl_bergabung' => '2026-01-17'],
            // Masyarakat umum
            ['no_cif' => 'CIF-MST-000026', 'nama' => 'Misdi Hartanto',            'no_ktp' => '3509010906580026', 'no_hp' => '081765430026', 'alamat' => 'Jl. Karimata No.28, Sumbersari, Jember',          'cabang_id' => $mst, 'tgl_bergabung' => '2026-01-19'],
            ['no_cif' => 'CIF-MST-000027', 'nama' => 'Suparno Wicaksono',         'no_ktp' => '3509011405630027', 'no_hp' => '082345670027', 'alamat' => 'Jl. Mastrip No.67, Kebonsari, Jember',             'cabang_id' => $mst, 'tgl_bergabung' => '2026-01-22'],
            ['no_cif' => 'CIF-MST-000028', 'nama' => 'Winarti Sulistyowati',      'no_ktp' => '3509014203710028', 'no_hp' => '081345670028', 'alamat' => 'Jl. Gajahmada No.33, Kaliwates, Jember',          'cabang_id' => $mst, 'tgl_bergabung' => '2026-01-25'],
            ['no_cif' => 'CIF-MST-000029', 'nama' => 'Slamet Riyadi Utomo',       'no_ktp' => '3509011012600029', 'no_hp' => '085698760029', 'alamat' => 'Jl. Brawijaya No.44, Kaliwates, Jember',          'cabang_id' => $mst, 'tgl_bergabung' => '2026-02-03'],
            ['no_cif' => 'CIF-MST-000030', 'nama' => 'Endang Sri Wahyuni',        'no_ktp' => '3509015607720030', 'no_hp' => '081765430030', 'alamat' => 'Jl. Kebonsari No.15, Sumbersari, Jember',         'cabang_id' => $mst, 'tgl_bergabung' => '2026-02-06'],
            ['no_cif' => 'CIF-MST-000031', 'nama' => 'Ponimin Hadi Susilo',       'no_ktp' => '3509010808580031', 'no_hp' => '089876540031', 'alamat' => 'Jl. Mastrip No.88, Kebonsari, Jember',             'cabang_id' => $mst, 'tgl_bergabung' => '2026-02-10'],
            ['no_cif' => 'CIF-MST-000032', 'nama' => 'Mulyadi Santoso',           'no_ktp' => '3509011205670032', 'no_hp' => '082198760032', 'alamat' => 'Jl. Wijaya Kusuma No.23, Kaliwates, Jember',      'cabang_id' => $mst, 'tgl_bergabung' => '2026-02-14'],
            ['no_cif' => 'CIF-MST-000033', 'nama' => 'Yanti Kusuma Dewi',         'no_ktp' => '3509016904800033', 'no_hp' => '085634560033', 'alamat' => 'Jl. Brawijaya No.12, Kaliwates, Jember',          'cabang_id' => $mst, 'tgl_bergabung' => '2026-02-18'],
            ['no_cif' => 'CIF-MST-000034', 'nama' => 'Teguh Pramono',             'no_ktp' => '3509011507720034', 'no_hp' => '081234560034', 'alamat' => 'Jl. Mastrip No.101, Kebonsari, Jember',            'cabang_id' => $mst, 'tgl_bergabung' => '2026-03-02'],
            ['no_cif' => 'CIF-MST-000035', 'nama' => 'Supri Haryanto',            'no_ktp' => '3509011208680035', 'no_hp' => '081357920035', 'alamat' => 'Jl. Kebonsari No.8, Sumbersari, Jember',          'cabang_id' => $mst, 'tgl_bergabung' => '2026-03-07'],
            ['no_cif' => 'CIF-MST-000036', 'nama' => 'Retno Ayu Wulandari',       'no_ktp' => '3509014010900036', 'no_hp' => '082145670036', 'alamat' => 'Perum Mastrip Indah Blok D No.3, Jember',         'cabang_id' => $mst, 'tgl_bergabung' => '2026-03-12'],
            ['no_cif' => 'CIF-MST-000037', 'nama' => 'Gunawan Setiadi',           'no_ktp' => '3509011406750037', 'no_hp' => '089654320037', 'alamat' => 'Jl. Gajahmada No.55, Kaliwates, Jember',          'cabang_id' => $mst, 'tgl_bergabung' => '2026-03-15'],
            ['no_cif' => 'CIF-MST-000038', 'nama' => 'Nur Hidayah Permata',       'no_ktp' => '3509015803900038', 'no_hp' => '085712340038', 'alamat' => 'Jl. Mastrip No.120, Kebonsari, Jember',            'cabang_id' => $mst, 'tgl_bergabung' => '2026-03-20'],

            // Cabang Karimata
            // Mahasiswa
            ['no_cif' => 'CIF-KRM-000001', 'nama' => 'Yohanes Fabian Surya',      'no_ktp' => '3509012005050039', 'no_hp' => '085634560039', 'alamat' => 'Jl. Jawa No.6, Sumbersari, Jember',               'cabang_id' => $krm, 'tgl_bergabung' => '2026-01-06'],
            ['no_cif' => 'CIF-KRM-000002', 'nama' => 'Alviansyah Nurhidayat',     'no_ktp' => '3509012809040040', 'no_hp' => '082198760040', 'alamat' => 'Jl. Wijaya Kusuma No.9, Kaliwates, Jember',        'cabang_id' => $krm, 'tgl_bergabung' => '2026-01-09'],
            ['no_cif' => 'CIF-KRM-000003', 'nama' => 'Muhammad Ilham Syahputra',  'no_ktp' => '3509011207050041', 'no_hp' => '081765430041', 'alamat' => 'Jl. Karimata No.11, Sumbersari, Jember',           'cabang_id' => $krm, 'tgl_bergabung' => '2026-01-13'],
            ['no_cif' => 'CIF-KRM-000004', 'nama' => 'Naufal Akbar Pratama',      'no_ktp' => '3509012709050042', 'no_hp' => '082345670042', 'alamat' => 'Jl. Sumatra No.22, Sumbersari, Jember',            'cabang_id' => $krm, 'tgl_bergabung' => '2026-01-16'],
            ['no_cif' => 'CIF-KRM-000005', 'nama' => 'Taufik Hidayatullah',       'no_ktp' => '3509011511040043', 'no_hp' => '085698760043', 'alamat' => 'Jl. Karimata No.44, Sumbersari, Jember',           'cabang_id' => $krm, 'tgl_bergabung' => '2026-01-20'],
            // Masyarakat umum
            ['no_cif' => 'CIF-KRM-000006', 'nama' => 'Ririn Dwi Agustin',         'no_ktp' => '3509014508000044', 'no_hp' => '081345670044', 'alamat' => 'Jl. Sumatra No.11, Sumbersari, Jember',            'cabang_id' => $krm, 'tgl_bergabung' => '2026-01-23'],
            ['no_cif' => 'CIF-KRM-000007', 'nama' => 'Juliana Intan Purwaningtyas','no_ktp' => '3509015207040045','no_hp' => '089876540045','alamat' => 'Jl. Brawijaya No.17, Kaliwates, Jember',            'cabang_id' => $krm, 'tgl_bergabung' => '2026-01-26'],
            ['no_cif' => 'CIF-KRM-000008', 'nama' => 'Misdi Sudarsono',           'no_ktp' => '3509010906580046', 'no_hp' => '081234560046', 'alamat' => 'Jl. Karimata No.28, Sumbersari, Jember',           'cabang_id' => $krm, 'tgl_bergabung' => '2026-02-02'],
            ['no_cif' => 'CIF-KRM-000009', 'nama' => 'Suparman Darmawan',         'no_ktp' => '3509011207640047', 'no_hp' => '082145670047', 'alamat' => 'Jl. Karimata No.56, Sumbersari, Jember',           'cabang_id' => $krm, 'tgl_bergabung' => '2026-02-07'],
            ['no_cif' => 'CIF-KRM-000010', 'nama' => 'Karmini Lestari',           'no_ktp' => '3509016601700048', 'no_hp' => '085712340048', 'alamat' => 'Jl. Jawa No.31, Sumbersari, Jember',              'cabang_id' => $krm, 'tgl_bergabung' => '2026-02-11'],
            ['no_cif' => 'CIF-KRM-000011', 'nama' => 'Paijo Hartoyo',             'no_ktp' => '3509010703580049', 'no_hp' => '081298760049', 'alamat' => 'Jl. Sumatra No.43, Sumbersari, Jember',            'cabang_id' => $krm, 'tgl_bergabung' => '2026-02-16'],
            ['no_cif' => 'CIF-KRM-000012', 'nama' => 'Suyatmi Rahayu',            'no_ktp' => '3509015804690050', 'no_hp' => '089654320050', 'alamat' => 'Jl. Karimata No.72, Sumbersari, Jember',           'cabang_id' => $krm, 'tgl_bergabung' => '2026-02-20'],
            ['no_cif' => 'CIF-KRM-000013', 'nama' => 'Sarwono Budiyanto',         'no_ktp' => '3509011509620051', 'no_hp' => '081765430051', 'alamat' => 'Jl. Karimata No.89, Sumbersari, Jember',           'cabang_id' => $krm, 'tgl_bergabung' => '2026-02-24'],
            ['no_cif' => 'CIF-KRM-000014', 'nama' => 'Asih Kusumawati',           'no_ktp' => '3509014802750052', 'no_hp' => '082345670052', 'alamat' => 'Jl. Sumatra No.67, Sumbersari, Jember',            'cabang_id' => $krm, 'tgl_bergabung' => '2026-03-03'],
            ['no_cif' => 'CIF-KRM-000015', 'nama' => 'Wahyu Setiawan Hadi',       'no_ktp' => '3509011310870053', 'no_hp' => '085634560053', 'alamat' => 'Jl. Karimata No.102, Sumbersari, Jember',          'cabang_id' => $krm, 'tgl_bergabung' => '2026-03-08'],
            ['no_cif' => 'CIF-KRM-000016', 'nama' => 'Tri Wahyuningsih',          'no_ktp' => '3509016007830054', 'no_hp' => '081357920054', 'alamat' => 'Jl. Jawa No.48, Sumbersari, Jember',              'cabang_id' => $krm, 'tgl_bergabung' => '2026-03-13'],
            ['no_cif' => 'CIF-KRM-000017', 'nama' => 'Prasetyo Nugroho',          'no_ktp' => '3509011703790055', 'no_hp' => '082198760055', 'alamat' => 'Jl. Karimata No.115, Sumbersari, Jember',          'cabang_id' => $krm, 'tgl_bergabung' => '2026-03-18'],
        ];

        foreach ($nasabah as $data) {
            DB::table('nasabah')->insert([
                'no_cif'        => $data['no_cif'],
                'nama'          => $data['nama'],
                'no_ktp'        => $data['no_ktp'],
                'no_hp'         => $data['no_hp'],
                'alamat'        => $data['alamat'],
                'cabang_id'     => $data['cabang_id'],
                'status'        => 'aktif',
                'tgl_bergabung' => $data['tgl_bergabung'],
                'created_by'    => $superadminId,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}