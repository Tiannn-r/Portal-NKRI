-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: portal_nkri
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint(20) unsigned DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `batch_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_log`
--

LOCK TABLES `activity_log` WRITE;
/*!40000 ALTER TABLE `activity_log` DISABLE KEYS */;
INSERT INTO `activity_log` VALUES (1,'default','created','App\\Models\\Application','created',1,NULL,NULL,'{\"attributes\":{\"name\":\"HRIS & Portal Kepegawaian\",\"slug\":\"hris-portal-kepegawaian\",\"description\":\"Sistem informasi pengelolaan data karyawan, cuti, klaim kesehatan, dan slip gaji elektronik.\",\"icon\":\"Users\",\"url\":\"https:\\/\\/hris.nkri.co.id\",\"app_category_id\":1,\"pic_user_id\":2,\"status\":\"active\",\"sort_order\":1,\"open_in_new_tab\":true,\"login_hint\":\"Gunakan NIK Karyawan & password portal HR Anda.\"}}',NULL,'2026-11-10 02:12:09','2026-11-10 02:12:09'),(2,'default','created','App\\Models\\Application','created',2,NULL,NULL,'{\"attributes\":{\"name\":\"ERP Supply Chain & Inventory\",\"slug\":\"erp-supply-chain-inventory\",\"description\":\"Platform terintegrasi pengelolaan stok barang, distribusi gudang, dan pemesanan logistik.\",\"icon\":\"Package\",\"url\":\"https:\\/\\/erp.nkri.co.id\",\"app_category_id\":3,\"pic_user_id\":2,\"status\":\"active\",\"sort_order\":2,\"open_in_new_tab\":true,\"login_hint\":\"Login dengan akun Odoo \\/ SAP korporat.\"}}',NULL,'2026-11-10 02:12:09','2026-11-10 02:12:09'),(3,'default','created','App\\Models\\Application','created',3,NULL,NULL,'{\"attributes\":{\"name\":\"Sistem Akuntansi & Faktur\",\"slug\":\"sistem-akuntansi-faktur\",\"description\":\"Aplikasi pencatatan jurnal umum, rekonsiliasi bank, e-Faktur pajak, dan pelaporan keuangan.\",\"icon\":\"Receipt\",\"url\":\"https:\\/\\/finance.nkri.co.id\",\"app_category_id\":2,\"pic_user_id\":2,\"status\":\"active\",\"sort_order\":3,\"open_in_new_tab\":true,\"login_hint\":\"Autentikasi dengan ID Akuntan resmi perusahaan.\"}}',NULL,'2026-11-10 02:12:09','2026-11-10 02:12:09'),(4,'default','created','App\\Models\\Application','created',4,NULL,NULL,'{\"attributes\":{\"name\":\"CRM & Manajemen Pelanggan\",\"slug\":\"crm-manajemen-pelanggan\",\"description\":\"Pelacakan prospek bisnis, pipeline penjualan, dan histori komunikasi klien korporat.\",\"icon\":\"Briefcase\",\"url\":\"https:\\/\\/crm.nkri.co.id\",\"app_category_id\":4,\"pic_user_id\":2,\"status\":\"active\",\"sort_order\":4,\"open_in_new_tab\":true,\"login_hint\":\"Gunakan akun tim Sales & Account Executive Anda.\"}}',NULL,'2026-11-10 02:12:09','2026-11-10 02:12:09'),(5,'default','created','App\\Models\\Application','created',5,NULL,NULL,'{\"attributes\":{\"name\":\"Helpdesk & IT Support Ticket\",\"slug\":\"helpdesk-it-support-ticket\",\"description\":\"Pusat pengaduan kendala perangkat keras, akses jaringan, dan permohonan lisensi software.\",\"icon\":\"LifeBuoy\",\"url\":\"https:\\/\\/helpdesk.nkri.co.id\",\"app_category_id\":5,\"pic_user_id\":2,\"status\":\"active\",\"sort_order\":5,\"open_in_new_tab\":true,\"login_hint\":\"Login dengan email internal @nkri.co.id Anda.\"}}',NULL,'2026-11-10 02:12:09','2026-11-10 02:12:09'),(6,'default','created','App\\Models\\Application','created',6,NULL,NULL,'{\"attributes\":{\"name\":\"E-Procurement Pengadaan Barang\",\"slug\":\"e-procurement-pengadaan-barang\",\"description\":\"Sistem tender elektronik, purchase request, purchase order, dan verifikasi vendor rekanan.\",\"icon\":\"ShoppingCart\",\"url\":\"https:\\/\\/procurement.nkri.co.id\",\"app_category_id\":3,\"pic_user_id\":2,\"status\":\"active\",\"sort_order\":6,\"open_in_new_tab\":true,\"login_hint\":\"Gunakan akun divisi Pengadaan yang terdaftar.\"}}',NULL,'2026-11-10 02:12:09','2026-11-10 02:12:09'),(7,'default','created','App\\Models\\Application','created',7,NULL,NULL,'{\"attributes\":{\"name\":\"Business Intelligence & Reporting\",\"slug\":\"business-intelligence-reporting\",\"description\":\"Dashboard analitik eksekutif untuk visualisasi performa bisnis dan laporan real-time.\",\"icon\":\"BarChart3\",\"url\":\"https:\\/\\/bi.nkri.co.id\",\"app_category_id\":5,\"pic_user_id\":2,\"status\":\"active\",\"sort_order\":7,\"open_in_new_tab\":true,\"login_hint\":\"Akses terbatas untuk pimpinan unit dan analis data.\"}}',NULL,'2026-11-10 02:12:09','2026-11-10 02:12:09'),(8,'default','created','App\\Models\\Application','created',8,NULL,NULL,'{\"attributes\":{\"name\":\"Portal Dokumen & Kontrak Legal\",\"slug\":\"portal-dokumen-kontrak-legal\",\"description\":\"Repositori terenkripsi draft perjanjian kerja sama, perizinan usaha, dan dokumen hukum perusahaan.\",\"icon\":\"FileText\",\"url\":\"https:\\/\\/legal.nkri.co.id\",\"app_category_id\":5,\"pic_user_id\":2,\"status\":\"active\",\"sort_order\":8,\"open_in_new_tab\":true,\"login_hint\":\"Akses terenkripsi divisi Legal & Kepatuhan.\"}}',NULL,'2026-11-10 02:12:09','2026-11-10 02:12:09'),(9,'default','created','App\\Models\\Application','created',9,NULL,NULL,'{\"attributes\":{\"name\":\"Aplikasi Presensi GPS Mobile\",\"slug\":\"aplikasi-presensi-gps-mobile\",\"description\":\"Aplikasi pencatatan kehadiran berbasis radius lokasi kantor dan pengenalan wajah.\",\"icon\":\"Clock\",\"url\":\"https:\\/\\/presensi.nkri.co.id\",\"app_category_id\":1,\"pic_user_id\":2,\"status\":\"maintenance\",\"sort_order\":9,\"open_in_new_tab\":true,\"login_hint\":\"Server dalam proses peningkatan keamanan berkala.\"}}',NULL,'2026-11-10 02:12:09','2026-11-10 02:12:09'),(10,'default','created','App\\Models\\Application','created',10,NULL,NULL,'{\"attributes\":{\"name\":\"Sistem Payroll Legacy V1 (Arsip)\",\"slug\":\"sistem-payroll-legacy-v1-arsip\",\"description\":\"Arsip sistem penggajian lama sebelum migrasi ke sistem HRIS modern.\",\"icon\":\"Archive\",\"url\":\"https:\\/\\/legacy-payroll.nkri.co.id\",\"app_category_id\":2,\"pic_user_id\":2,\"status\":\"inactive\",\"sort_order\":10,\"open_in_new_tab\":true,\"login_hint\":\"Sistem tidak lagi menerima input data baru.\"}}',NULL,'2026-11-10 02:12:09','2026-11-10 02:12:09'),(11,'default','created','App\\Models\\Announcement','created',1,NULL,NULL,'{\"attributes\":{\"title\":\"Pemeliharaan Rutin Server Pusat Akhir Pekan\",\"body\":\"Diberitahukan kepada seluruh karyawan bahwa akan dilaksanakan pemeliharaan server pusat pada hari Sabtu, 14 November pukul 22.00 WIB s\\/d Minggu, 15 November pukul 04.00 WIB. Selama masa pemeliharaan, beberapa sistem seperti ERP dan Presensi Mobile mungkin tidak dapat diakses sementara waktu.\",\"is_published\":true,\"starts_at\":\"2026-11-09T02:12:09.000000Z\",\"ends_at\":\"2026-11-17T02:12:09.000000Z\",\"created_by\":1}}',NULL,'2026-11-10 02:12:09','2026-11-10 02:12:09'),(12,'default','created','App\\Models\\Announcement','created',2,NULL,NULL,'{\"attributes\":{\"title\":\"Pembaruan Kebijakan Keamanan Akun & Sandi Berkala\",\"body\":\"Sesuai standar kepatuhan ISO 27001 dan UU PDP, seluruh karyawan diwajibkan memperbarui kata sandi sistem minimal setiap 90 hari. Pastikan kata sandi baru mengandung minimal 8 karakter, huruf besar, angka, dan simbol.\",\"is_published\":true,\"starts_at\":\"2026-11-08T02:12:09.000000Z\",\"ends_at\":\"2026-11-24T02:12:09.000000Z\",\"created_by\":1}}',NULL,'2026-11-10 02:12:09','2026-11-10 02:12:09'),(13,'default','updated','App\\Models\\Application','updated',1,'App\\Models\\User',1,'{\"attributes\":{\"login_hint\":null},\"old\":{\"login_hint\":\"Gunakan NIK Karyawan & password portal HR Anda.\"}}',NULL,'2026-11-10 07:02:42','2026-11-10 07:02:42'),(14,'default','updated','App\\Models\\Application','updated',1,NULL,NULL,'{\"attributes\":{\"image\":\"images\\/apps\\/hris.svg\",\"login_hint\":\"Gunakan NIK Karyawan & password portal HR Anda.\"},\"old\":{\"image\":null,\"login_hint\":null}}',NULL,'2026-11-10 07:12:11','2026-11-10 07:12:11'),(15,'default','updated','App\\Models\\Application','updated',2,NULL,NULL,'{\"attributes\":{\"image\":\"images\\/apps\\/erp.svg\"},\"old\":{\"image\":null}}',NULL,'2026-11-10 07:12:11','2026-11-10 07:12:11'),(16,'default','updated','App\\Models\\Application','updated',3,NULL,NULL,'{\"attributes\":{\"image\":\"images\\/apps\\/finance.svg\"},\"old\":{\"image\":null}}',NULL,'2026-11-10 07:12:11','2026-11-10 07:12:11'),(17,'default','updated','App\\Models\\Application','updated',4,NULL,NULL,'{\"attributes\":{\"image\":\"images\\/apps\\/crm.svg\"},\"old\":{\"image\":null}}',NULL,'2026-11-10 07:12:11','2026-11-10 07:12:11'),(18,'default','updated','App\\Models\\Application','updated',5,NULL,NULL,'{\"attributes\":{\"image\":\"images\\/apps\\/helpdesk.svg\"},\"old\":{\"image\":null}}',NULL,'2026-11-10 07:12:11','2026-11-10 07:12:11'),(19,'default','updated','App\\Models\\Application','updated',6,NULL,NULL,'{\"attributes\":{\"image\":\"images\\/apps\\/procurement.svg\"},\"old\":{\"image\":null}}',NULL,'2026-11-10 07:12:11','2026-11-10 07:12:11'),(20,'default','updated','App\\Models\\Application','updated',7,NULL,NULL,'{\"attributes\":{\"image\":\"images\\/apps\\/bi.svg\"},\"old\":{\"image\":null}}',NULL,'2026-11-10 07:12:11','2026-11-10 07:12:11'),(21,'default','updated','App\\Models\\Application','updated',8,NULL,NULL,'{\"attributes\":{\"image\":\"images\\/apps\\/legal.svg\"},\"old\":{\"image\":null}}',NULL,'2026-11-10 07:12:11','2026-11-10 07:12:11'),(22,'default','updated','App\\Models\\Application','updated',9,NULL,NULL,'{\"attributes\":{\"image\":\"images\\/apps\\/attendance.svg\"},\"old\":{\"image\":null}}',NULL,'2026-11-10 07:12:11','2026-11-10 07:12:11'),(23,'default','updated','App\\Models\\Application','updated',1,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Portal Sistem NKRI\",\"icon\":\"Layers\",\"logo\":\"applications\\/logos\\/Gm7IL8tEebtJxqYueRjY4vWU6KUBKBRSfbQmOTxT.png\",\"image\":\"applications\\/previews\\/kU0ekptn0BYie6xa8wKSIoq8EcWtoa97gNSxumwq.png\",\"url\":\"http:\\/\\/127.0.0.1:8080\\/\",\"app_category_id\":5,\"pic_user_id\":6,\"login_hint\":\"Gunakan gmail dan password yang sudah diberikan\"},\"old\":{\"name\":\"HRIS & Portal Kepegawaian\",\"icon\":\"Users\",\"logo\":null,\"image\":\"images\\/apps\\/hris.svg\",\"url\":\"https:\\/\\/hris.nkri.co.id\",\"app_category_id\":1,\"pic_user_id\":null,\"login_hint\":\"Gunakan NIK Karyawan & password portal HR Anda.\"}}',NULL,'2026-11-10 07:20:00','2026-11-10 07:20:00'),(24,'default','updated','App\\Models\\Application','updated',2,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Portal ODOO\",\"icon\":\"Building\",\"logo\":\"applications\\/logos\\/qoT5B6zqsH1gfUhLodQa6qIVobum2HB8DDCJHzpX.png\",\"image\":\"applications\\/previews\\/zFAwdxwF4OWWEOlY0ha7wwKnHpXbLz4DbF15MHVx.png\",\"url\":\"http:\\/\\/odoo.nanggala.co.id:8068\\/web\\/login\",\"app_category_id\":1,\"pic_user_id\":6,\"login_hint\":\"Login dengan gmail yang terdaftar di ODOO dan password yang sudah dibuatkan\"},\"old\":{\"name\":\"ERP Supply Chain & Inventory\",\"icon\":\"Package\",\"logo\":null,\"image\":\"images\\/apps\\/erp.svg\",\"url\":\"https:\\/\\/erp.nkri.co.id\",\"app_category_id\":3,\"pic_user_id\":null,\"login_hint\":\"Login dengan akun Odoo \\/ SAP korporat.\"}}',NULL,'2026-11-10 07:33:58','2026-11-10 07:33:58'),(25,'default','updated','App\\Models\\Application','updated',1,'App\\Models\\User',1,'{\"attributes\":{\"app_category_id\":7},\"old\":{\"app_category_id\":null}}',NULL,'2026-11-10 07:41:44','2026-11-10 07:41:44'),(26,'default','updated','App\\Models\\Application','updated',2,'App\\Models\\User',1,'{\"attributes\":{\"app_category_id\":6},\"old\":{\"app_category_id\":null}}',NULL,'2026-11-10 07:41:55','2026-11-10 07:41:55'),(27,'default','updated','App\\Models\\Application','updated',3,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Sistem Final Assembly\",\"description\":\"Sistem yang dibuat untuk membantu dan mengaudit proses final assembly\",\"logo\":\"applications\\/logos\\/hIkrgoC6yC6Ih8qflbIynmHtdUDqlWC3WtwyxoqD.png\",\"image\":\"applications\\/previews\\/9NTUwnv5dPavqfLiuLpYM0UIKzPvoIP7DOfQ3Sxe.png\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/\",\"app_category_id\":7,\"pic_user_id\":6,\"login_hint\":\"Login dengan akun dan password yang sudah diberikan\"},\"old\":{\"name\":\"Sistem Akuntansi & Faktur\",\"description\":\"Aplikasi pencatatan jurnal umum, rekonsiliasi bank, e-Faktur pajak, dan pelaporan keuangan.\",\"logo\":null,\"image\":\"images\\/apps\\/finance.svg\",\"url\":\"https:\\/\\/finance.nkri.co.id\",\"app_category_id\":null,\"pic_user_id\":null,\"login_hint\":\"Autentikasi dengan ID Akuntan resmi perusahaan.\"}}',NULL,'2026-11-10 07:43:43','2026-11-10 07:43:43'),(28,'default','updated','App\\Models\\Application','updated',3,'App\\Models\\User',1,'{\"attributes\":{\"image\":\"applications\\/previews\\/ofwhvjETf0lqK34NUyLkP7wuiqCFc6cgpIediiSF.png\"},\"old\":{\"image\":\"applications\\/previews\\/9NTUwnv5dPavqfLiuLpYM0UIKzPvoIP7DOfQ3Sxe.png\"}}',NULL,'2026-11-10 07:45:00','2026-11-10 07:45:00'),(29,'default','updated','App\\Models\\Announcement','updated',1,'App\\Models\\User',1,'{\"attributes\":{\"title\":\"Perubahan Jam Operasional Untuk Long Shift 1 dan 2\",\"body\":\"<p>Diberitahukan kepada seluruh karyawan NKRI, bahwa di mulai dari tanggal 2 November 2026 dan seterusnya jam kerja Long Shift (LS)&nbsp; akan mengalami perubahan, yang sebelumnya sebanyak 12 jam, sekarang mengalami kenaikan jam sebanyak 15 menit, sehingga jam kerja karyawan Long Shift (LS) menjadi 12 jam lebih 15 menit.&nbsp;<\\/p>\",\"ends_at\":null},\"old\":{\"title\":\"Pemeliharaan Rutin Server Pusat Akhir Pekan\",\"body\":\"Diberitahukan kepada seluruh karyawan bahwa akan dilaksanakan pemeliharaan server pusat pada hari Sabtu, 14 November pukul 22.00 WIB s\\/d Minggu, 15 November pukul 04.00 WIB. Selama masa pemeliharaan, beberapa sistem seperti ERP dan Presensi Mobile mungkin tidak dapat diakses sementara waktu.\",\"ends_at\":\"2026-11-17T02:12:09.000000Z\"}}',NULL,'2026-11-10 07:51:27','2026-11-10 07:51:27'),(30,'default','updated','App\\Models\\Announcement','updated',1,'App\\Models\\User',1,'{\"attributes\":{\"title\":\"Perubahan Jam Kerja Untuk Long Shift 1 dan 2\"},\"old\":{\"title\":\"Perubahan Jam Operasional Untuk Long Shift 1 dan 2\"}}',NULL,'2026-11-10 08:13:01','2026-11-10 08:13:01'),(31,'default','updated','App\\Models\\Announcement','updated',2,'App\\Models\\User',1,'{\"attributes\":{\"title\":\"Pembaruan Aturan Sebelum Pulang\",\"body\":\"<p>Disesuaikan dengan sudah ada nya satpam di wiiayah NKRI maka, Karyawan dan Staff di NKRI mulai tanggal 4 November 2026 diwajibkan sebelum pulang untuk dilakukannya pemeriksaan fisik oleh satpan yang bekerja.<\\/p>\",\"ends_at\":null},\"old\":{\"title\":\"Pembaruan Kebijakan Keamanan Akun & Sandi Berkala\",\"body\":\"Sesuai standar kepatuhan ISO 27001 dan UU PDP, seluruh karyawan diwajibkan memperbarui kata sandi sistem minimal setiap 90 hari. Pastikan kata sandi baru mengandung minimal 8 karakter, huruf besar, angka, dan simbol.\",\"ends_at\":\"2026-11-24T02:12:09.000000Z\"}}',NULL,'2026-11-10 08:15:06','2026-11-10 08:15:06'),(32,'default','deleted','App\\Models\\Application','deleted',4,'App\\Models\\User',1,'{\"old\":{\"name\":\"CRM & Manajemen Pelanggan\",\"slug\":\"crm-manajemen-pelanggan\",\"description\":\"Pelacakan prospek bisnis, pipeline penjualan, dan histori komunikasi klien korporat.\",\"icon\":\"Briefcase\",\"logo\":null,\"image\":\"images\\/apps\\/crm.svg\",\"url\":\"https:\\/\\/crm.nkri.co.id\",\"app_category_id\":null,\"pic_user_id\":null,\"status\":\"active\",\"sort_order\":4,\"open_in_new_tab\":true,\"login_hint\":\"Gunakan akun tim Sales & Account Executive Anda.\"}}',NULL,'2026-11-10 08:16:57','2026-11-10 08:16:57'),(33,'default','deleted','App\\Models\\Application','deleted',5,'App\\Models\\User',1,'{\"old\":{\"name\":\"Helpdesk & IT Support Ticket\",\"slug\":\"helpdesk-it-support-ticket\",\"description\":\"Pusat pengaduan kendala perangkat keras, akses jaringan, dan permohonan lisensi software.\",\"icon\":\"LifeBuoy\",\"logo\":null,\"image\":\"images\\/apps\\/helpdesk.svg\",\"url\":\"https:\\/\\/helpdesk.nkri.co.id\",\"app_category_id\":null,\"pic_user_id\":null,\"status\":\"active\",\"sort_order\":5,\"open_in_new_tab\":true,\"login_hint\":\"Login dengan email internal @nkri.co.id Anda.\"}}',NULL,'2026-11-10 08:17:00','2026-11-10 08:17:00'),(34,'default','deleted','App\\Models\\Application','deleted',6,'App\\Models\\User',1,'{\"old\":{\"name\":\"E-Procurement Pengadaan Barang\",\"slug\":\"e-procurement-pengadaan-barang\",\"description\":\"Sistem tender elektronik, purchase request, purchase order, dan verifikasi vendor rekanan.\",\"icon\":\"ShoppingCart\",\"logo\":null,\"image\":\"images\\/apps\\/procurement.svg\",\"url\":\"https:\\/\\/procurement.nkri.co.id\",\"app_category_id\":null,\"pic_user_id\":null,\"status\":\"active\",\"sort_order\":6,\"open_in_new_tab\":true,\"login_hint\":\"Gunakan akun divisi Pengadaan yang terdaftar.\"}}',NULL,'2026-11-10 08:17:03','2026-11-10 08:17:03'),(35,'default','deleted','App\\Models\\Application','deleted',7,'App\\Models\\User',1,'{\"old\":{\"name\":\"Business Intelligence & Reporting\",\"slug\":\"business-intelligence-reporting\",\"description\":\"Dashboard analitik eksekutif untuk visualisasi performa bisnis dan laporan real-time.\",\"icon\":\"BarChart3\",\"logo\":null,\"image\":\"images\\/apps\\/bi.svg\",\"url\":\"https:\\/\\/bi.nkri.co.id\",\"app_category_id\":null,\"pic_user_id\":null,\"status\":\"active\",\"sort_order\":7,\"open_in_new_tab\":true,\"login_hint\":\"Akses terbatas untuk pimpinan unit dan analis data.\"}}',NULL,'2026-11-10 08:17:05','2026-11-10 08:17:05'),(36,'default','deleted','App\\Models\\Application','deleted',8,'App\\Models\\User',1,'{\"old\":{\"name\":\"Portal Dokumen & Kontrak Legal\",\"slug\":\"portal-dokumen-kontrak-legal\",\"description\":\"Repositori terenkripsi draft perjanjian kerja sama, perizinan usaha, dan dokumen hukum perusahaan.\",\"icon\":\"FileText\",\"logo\":null,\"image\":\"images\\/apps\\/legal.svg\",\"url\":\"https:\\/\\/legal.nkri.co.id\",\"app_category_id\":null,\"pic_user_id\":null,\"status\":\"active\",\"sort_order\":8,\"open_in_new_tab\":true,\"login_hint\":\"Akses terenkripsi divisi Legal & Kepatuhan.\"}}',NULL,'2026-11-10 08:17:08','2026-11-10 08:17:08'),(37,'default','deleted','App\\Models\\Application','deleted',9,'App\\Models\\User',1,'{\"old\":{\"name\":\"Aplikasi Presensi GPS Mobile\",\"slug\":\"aplikasi-presensi-gps-mobile\",\"description\":\"Aplikasi pencatatan kehadiran berbasis radius lokasi kantor dan pengenalan wajah.\",\"icon\":\"Clock\",\"logo\":null,\"image\":\"images\\/apps\\/attendance.svg\",\"url\":\"https:\\/\\/presensi.nkri.co.id\",\"app_category_id\":null,\"pic_user_id\":null,\"status\":\"maintenance\",\"sort_order\":9,\"open_in_new_tab\":true,\"login_hint\":\"Server dalam proses peningkatan keamanan berkala.\"}}',NULL,'2026-11-10 08:17:12','2026-11-10 08:17:12'),(38,'default','deleted','App\\Models\\Application','deleted',10,'App\\Models\\User',1,'{\"old\":{\"name\":\"Sistem Payroll Legacy V1 (Arsip)\",\"slug\":\"sistem-payroll-legacy-v1-arsip\",\"description\":\"Arsip sistem penggajian lama sebelum migrasi ke sistem HRIS modern.\",\"icon\":\"Archive\",\"logo\":null,\"image\":null,\"url\":\"https:\\/\\/legacy-payroll.nkri.co.id\",\"app_category_id\":null,\"pic_user_id\":null,\"status\":\"inactive\",\"sort_order\":10,\"open_in_new_tab\":true,\"login_hint\":\"Sistem tidak lagi menerima input data baru.\"}}',NULL,'2026-11-10 08:17:14','2026-11-10 08:17:14'),(39,'default','updated','App\\Models\\Application','updated',2,'App\\Models\\User',1,'{\"attributes\":{\"name\":\"Portal ODOO NKRI\",\"url\":\"http:\\/\\/odoo.nanggala.co.id:8068\\/web\\/login?redirect=%2Fodoo%3Fdb%3Ddb_nkri_prodcopy_20261006\"},\"old\":{\"name\":\"Portal ODOO\",\"url\":\"http:\\/\\/odoo.nanggala.co.id:8068\\/web\\/login\"}}',NULL,'2026-11-10 08:17:30','2026-11-10 08:17:30'),(40,'default','updated','App\\Models\\Application','updated',3,'App\\Models\\User',1,'{\"attributes\":{\"image\":\"applications\\/previews\\/mIJQ6VVF7cboMjsVB8YY6UutUZ998UqXZ76ortD1.png\"},\"old\":{\"image\":\"applications\\/previews\\/ofwhvjETf0lqK34NUyLkP7wuiqCFc6cgpIediiSF.png\"}}',NULL,'2026-11-11 02:18:06','2026-11-11 02:18:06'),(41,'default','updated','App\\Models\\Application','updated',1,'App\\Models\\User',1,'{\"attributes\":{\"image\":\"applications\\/previews\\/qkbIAzXI3QlkWamjZ8EbP7GxztgBe6ITMbhOvf9O.png\"},\"old\":{\"image\":\"applications\\/previews\\/kU0ekptn0BYie6xa8wKSIoq8EcWtoa97gNSxumwq.png\"}}',NULL,'2026-11-11 03:54:02','2026-11-11 03:54:02');
/*!40000 ALTER TABLE `activity_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `announcements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `announcements_created_by_foreign` (`created_by`),
  KEY `announcements_is_published_starts_at_ends_at_index` (`is_published`,`starts_at`,`ends_at`),
  CONSTRAINT `announcements_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements`
--

LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;
INSERT INTO `announcements` VALUES (1,'Perubahan Jam Kerja Untuk Long Shift 1 dan 2','<p>Diberitahukan kepada seluruh karyawan NKRI, bahwa di mulai dari tanggal 2 November 2026 dan seterusnya jam kerja Long Shift (LS)&nbsp; akan mengalami perubahan, yang sebelumnya sebanyak 12 jam, sekarang mengalami kenaikan jam sebanyak 15 menit, sehingga jam kerja karyawan Long Shift (LS) menjadi 12 jam lebih 15 menit.&nbsp;</p>',1,'2026-11-09 02:12:09',NULL,1,'2026-11-10 02:12:09','2026-11-10 08:13:01'),(2,'Pembaruan Aturan Sebelum Pulang','<p>Disesuaikan dengan sudah ada nya satpam di wiiayah NKRI maka, Karyawan dan Staff di NKRI mulai tanggal 4 November 2026 diwajibkan sebelum pulang untuk dilakukannya pemeriksaan fisik oleh satpan yang bekerja.</p>',1,'2026-11-08 02:12:09',NULL,1,'2026-11-10 02:12:09','2026-11-10 08:15:06');
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_categories`
--

DROP TABLE IF EXISTS `app_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_categories_slug_unique` (`slug`),
  KEY `app_categories_sort_order_index` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_categories`
--

LOCK TABLES `app_categories` WRITE;
/*!40000 ALTER TABLE `app_categories` DISABLE KEYS */;
INSERT INTO `app_categories` VALUES (6,'Sistem ODOO','sistem-odoo',0,'2026-11-10 07:34:40','2026-11-10 07:34:40'),(7,'Website Sistem','website-sistem',0,'2026-11-10 07:41:26','2026-11-10 07:41:26');
/*!40000 ALTER TABLE `app_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_launch_logs`
--

DROP TABLE IF EXISTS `app_launch_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_launch_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `application_id` bigint(20) unsigned NOT NULL,
  `launched_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `app_launch_logs_user_id_launched_at_index` (`user_id`,`launched_at`),
  KEY `app_launch_logs_application_id_launched_at_index` (`application_id`,`launched_at`),
  CONSTRAINT `app_launch_logs_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  CONSTRAINT `app_launch_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_launch_logs`
--

LOCK TABLES `app_launch_logs` WRITE;
/*!40000 ALTER TABLE `app_launch_logs` DISABLE KEYS */;
INSERT INTO `app_launch_logs` VALUES (1,4,1,'2026-11-10 06:46:56'),(2,4,1,'2026-11-10 08:15:25'),(3,4,2,'2026-11-10 08:15:27'),(4,4,2,'2026-11-10 08:17:38'),(5,4,3,'2026-11-10 08:19:52'),(6,4,2,'2026-11-11 02:18:46'),(7,4,1,'2026-11-11 03:04:24'),(8,4,2,'2026-11-11 03:04:26'),(9,4,3,'2026-11-11 03:04:30'),(10,4,1,'2026-11-11 03:30:36'),(11,1,2,'2026-11-12 01:55:10'),(12,1,2,'2026-11-12 08:49:39');
/*!40000 ALTER TABLE `app_launch_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `application_department`
--

DROP TABLE IF EXISTS `application_department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_department` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `application_id` bigint(20) unsigned NOT NULL,
  `department_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `application_department_application_id_department_id_unique` (`application_id`,`department_id`),
  KEY `application_department_department_id_foreign` (`department_id`),
  CONSTRAINT `application_department_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  CONSTRAINT `application_department_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `application_department`
--

LOCK TABLES `application_department` WRITE;
/*!40000 ALTER TABLE `application_department` DISABLE KEYS */;
/*!40000 ALTER TABLE `application_department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `application_role`
--

DROP TABLE IF EXISTS `application_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_role` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `application_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `application_role_application_id_role_id_unique` (`application_id`,`role_id`),
  KEY `application_role_role_id_foreign` (`role_id`),
  CONSTRAINT `application_role_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  CONSTRAINT `application_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `application_role`
--

LOCK TABLES `application_role` WRITE;
/*!40000 ALTER TABLE `application_role` DISABLE KEYS */;
/*!40000 ALTER TABLE `application_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `application_user`
--

DROP TABLE IF EXISTS `application_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `application_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `type` enum('allow','deny') NOT NULL DEFAULT 'allow',
  `notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `application_user_application_id_user_id_unique` (`application_id`,`user_id`),
  KEY `application_user_user_id_type_index` (`user_id`,`type`),
  CONSTRAINT `application_user_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  CONSTRAINT `application_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `application_user`
--

LOCK TABLES `application_user` WRITE;
/*!40000 ALTER TABLE `application_user` DISABLE KEYS */;
INSERT INTO `application_user` VALUES (6,1,3,'allow','Hak akses langsung pengguna','2026-11-10 02:12:09','2026-11-10 02:12:09'),(8,3,4,'allow','Hak akses langsung pengguna','2026-11-10 02:12:09','2026-11-10 02:12:09'),(9,2,4,'allow','Hak akses langsung pengguna','2026-11-10 02:12:09','2026-11-10 02:12:09'),(11,2,5,'allow','Hak akses langsung pengguna','2026-11-10 02:12:09','2026-11-10 02:12:09'),(19,1,1,'allow',NULL,'2026-11-10 03:37:06','2026-11-10 03:37:06'),(20,2,1,'allow',NULL,'2026-11-10 03:37:06','2026-11-10 03:37:06'),(21,3,1,'allow',NULL,'2026-11-10 03:37:06','2026-11-10 03:37:06'),(28,1,4,'allow',NULL,'2026-11-10 03:37:20','2026-11-10 03:37:20'),(30,1,6,'allow',NULL,'2026-11-11 08:13:27','2026-11-11 08:13:27'),(31,2,6,'allow',NULL,'2026-11-11 08:13:27','2026-11-11 08:13:27'),(32,3,6,'allow',NULL,'2026-11-11 08:13:27','2026-11-11 08:13:27'),(33,1,5,'allow',NULL,'2026-11-11 08:13:52','2026-11-11 08:13:52'),(34,2,3,'allow',NULL,'2026-11-11 08:13:56','2026-11-11 08:13:56');
/*!40000 ALTER TABLE `application_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `applications`
--

DROP TABLE IF EXISTS `applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `applications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `logo` varchar(2048) DEFAULT NULL,
  `image` varchar(2048) DEFAULT NULL,
  `url` varchar(2048) NOT NULL,
  `app_category_id` bigint(20) unsigned DEFAULT NULL,
  `pic_user_id` bigint(20) unsigned DEFAULT NULL,
  `status` enum('active','maintenance','inactive') NOT NULL DEFAULT 'active',
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `open_in_new_tab` tinyint(1) NOT NULL DEFAULT 1,
  `login_hint` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `applications_slug_unique` (`slug`),
  KEY `applications_pic_user_id_foreign` (`pic_user_id`),
  KEY `applications_status_sort_order_index` (`status`,`sort_order`),
  KEY `applications_app_category_id_status_index` (`app_category_id`,`status`),
  CONSTRAINT `applications_app_category_id_foreign` FOREIGN KEY (`app_category_id`) REFERENCES `app_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `applications_pic_user_id_foreign` FOREIGN KEY (`pic_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `applications`
--

LOCK TABLES `applications` WRITE;
/*!40000 ALTER TABLE `applications` DISABLE KEYS */;
INSERT INTO `applications` VALUES (1,'Portal Sistem NKRI','hris-portal-kepegawaian','Sistem informasi pengelolaan data karyawan, cuti, klaim kesehatan, dan slip gaji elektronik.','Layers','applications/logos/Gm7IL8tEebtJxqYueRjY4vWU6KUBKBRSfbQmOTxT.png','applications/previews/qkbIAzXI3QlkWamjZ8EbP7GxztgBe6ITMbhOvf9O.png','http://127.0.0.1:8080/',7,6,'active',1,1,'Gunakan gmail dan password yang sudah diberikan','2026-11-10 02:12:09','2026-11-11 03:54:02'),(2,'Portal ODOO NKRI','erp-supply-chain-inventory','Platform terintegrasi pengelolaan stok barang, distribusi gudang, dan pemesanan logistik.','Building','applications/logos/qoT5B6zqsH1gfUhLodQa6qIVobum2HB8DDCJHzpX.png','applications/previews/zFAwdxwF4OWWEOlY0ha7wwKnHpXbLz4DbF15MHVx.png','http://odoo.nanggala.co.id:8068/web/login?redirect=%2Fodoo%3Fdb%3Ddb_nkri_prodcopy_20261006',6,6,'active',2,1,'Login dengan gmail yang terdaftar di ODOO dan password yang sudah dibuatkan','2026-11-10 02:12:09','2026-11-10 08:17:30'),(3,'Sistem Final Assembly','sistem-akuntansi-faktur','Sistem yang dibuat untuk membantu dan mengaudit proses final assembly','Receipt','applications/logos/hIkrgoC6yC6Ih8qflbIynmHtdUDqlWC3WtwyxoqD.png','applications/previews/mIJQ6VVF7cboMjsVB8YY6UutUZ998UqXZ76ortD1.png','http://127.0.0.1:8000/',7,6,'active',3,1,'Login dengan akun dan password yang sudah diberikan','2026-11-10 02:12:09','2026-11-11 02:18:06');
/*!40000 ALTER TABLE `applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3','i:1;',1794471942),('livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer','i:1794471942;',1794471942),('spatie.permission.cache','a:3:{s:5:\"alias\";a:0:{}s:11:\"permissions\";a:0:{}s:5:\"roles\";a:0:{}}',1794558287);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `departments_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'Teknologi Informasi','IT','Divisi pengembangan sistem, infrastruktur jaringan, dan dukungan teknis internal.','2026-11-10 02:12:08','2026-11-10 02:12:08'),(2,'Sumber Daya Manusia','HR','Divisi manajemen kepegawaian, kompensasi, dan pengembangan talenta.','2026-11-10 02:12:08','2026-11-10 02:12:08'),(3,'Keuangan & Akuntansi','FIN','Divisi pelaporan keuangan, perpajakan, anggaran, dan perbendaharaan.','2026-11-10 02:12:08','2026-11-10 02:12:08'),(4,'Operasional & Logistik','OPS','Divisi rantai pasok, manajemen pergudangan, dan operasional harian.','2026-11-10 02:12:08','2026-11-10 02:12:08'),(5,'Pemasaran & Penjualan','MKT','Divisi ekspansi pasar, akuisisi pelanggan, dan komunikasi korporat.','2026-11-10 02:12:08','2026-11-10 02:12:08'),(6,'Legal & Kepatuhan','LGL','Divisi hukum, audit kepatuhan regulasi, dan perjanjian kontrak.','2026-11-10 02:12:08','2026-11-10 02:12:08');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_11_09_104516_create_permission_tables',1),(5,'2026_11_09_104518_create_activity_log_table',1),(6,'2026_11_09_104519_add_event_column_to_activity_log_table',1),(7,'2026_11_09_104520_add_batch_uuid_column_to_activity_log_table',1),(8,'2026_11_09_105219_create_departments_table',1),(9,'2026_11_09_105220_add_department_and_status_to_users_table',1),(10,'2026_11_09_105220_create_app_categories_table',1),(11,'2026_11_09_105221_create_applications_table',1),(12,'2026_11_09_105222_create_access_matrix_tables',1),(13,'2026_11_09_105222_create_user_favorites_and_launch_logs_tables',1),(14,'2026_11_09_105223_create_announcements_and_settings_tables',1),(15,'2026_11_10_140749_add_image_and_logo_to_applications_table',2),(16,'2026_11_10_143017_make_slug_nullable_in_applications_table',3),(17,'2026_11_10_143615_update_app_category_foreign_to_null_on_delete',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(1,'App\\Models\\User',4),(1,'App\\Models\\User',6),(3,'App\\Models\\User',3),(4,'App\\Models\\User',4),(4,'App\\Models\\User',5),(4,'App\\Models\\User',6);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Super Admin','web','2026-11-10 02:12:08','2026-11-10 02:12:08'),(2,'Admin Sistem','web','2026-11-10 02:12:08','2026-11-10 02:12:08'),(3,'Admin Konten','web','2026-11-10 02:12:08','2026-11-10 02:12:08'),(4,'User','web','2026-11-10 02:12:08','2026-11-10 02:12:08');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('OYuY2e7ne7KObDODBsVrclpa9JQrKbwM5CuraCqf',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiWTdoQndXR1Y4enE4d3h2TnBFNmVRTTZHdWRHZncybXUwUXIxd1liQiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgxMDAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUyOiJsb2dpbl9hZG1pbl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxOToicGFzc3dvcmRfaGFzaF9hZG1pbiI7czo2MDoiJDJ5JDEyJHNKTkE4UjUyQWpNb2NTUkwwSkd1by51NUpOWHh1NzhWRzhDTGVxUWg3MjFmZEI0SXJMeXJHIjtzOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1794474058);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'string',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'hero_title','Selamat datang di Portal NKRI','string','2026-11-10 02:12:09','2026-11-10 02:12:09'),(2,'hero_description','Penyimpan seluruh sistem yang dibuat untuk PT NKRI','string','2026-11-10 02:12:09','2026-11-10 02:12:09'),(3,'footer_text','PT Nanggala Kencana Rekatama Indonesia. Seluruh hak cipta dilindungi.','string','2026-11-10 02:12:09','2026-11-10 02:12:09');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_favorites`
--

DROP TABLE IF EXISTS `user_favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_favorites` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `application_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_favorites_user_id_application_id_unique` (`user_id`,`application_id`),
  KEY `user_favorites_application_id_foreign` (`application_id`),
  CONSTRAINT `user_favorites_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_favorites`
--

LOCK TABLES `user_favorites` WRITE;
/*!40000 ALTER TABLE `user_favorites` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `department_id` bigint(20) unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_department_id_is_active_index` (`department_id`,`is_active`),
  CONSTRAINT `users_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Administrator','admin@gmail.com','2026-11-10 02:31:08','$2y$12$sJNA8R52AjMocSRL0JGuo.u5JNXxu78VG8CLeqQh721fdB4IrLyrG',1,1,NULL,'2026-11-10 02:12:08','2026-11-10 03:05:40'),(3,'Dimas Panji','dimaspanji@gmail.com','2026-11-10 02:12:09','$2y$12$YeezGCC/JzWsRfQ539j/U.1LVwggniJqpqDU3gGc6hUxhTP7XjfsC',2,1,NULL,'2026-11-10 02:12:09','2026-11-10 03:06:34'),(4,'Muhamad Ibnu','muhamadibnu@gmail.com','2026-11-10 02:12:09','$2y$12$PrN/KQVp3ckgzY59RM1mfe/Yi18Q779LYpfH90IZbbuwkHOVt9.4.',3,1,NULL,'2026-11-10 02:12:09','2026-11-10 02:55:25'),(5,'Egy Sukmawan','egysukmawan@gmail.com','2026-11-10 02:12:09','$2y$12$dw9U3qf2zl1QJjWzb9Kl4.b/J32tOrFdHOnuCDxvfYvbglm2ArW0e',4,1,NULL,'2026-11-10 02:12:09','2026-11-11 08:14:43'),(6,'Miranti Lestari','mirantilestari@gmail.com','2026-11-10 02:12:09','$2y$12$WgaNadB5CPrcvTofQaAgduUoCVRfJ9UaiLsh6PL1M75iplABir7Y.',5,1,NULL,'2026-11-10 02:12:09','2026-11-10 03:04:38');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-16  8:20:22
