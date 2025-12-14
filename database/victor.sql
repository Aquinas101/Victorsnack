/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.5.5-10.4.32-MariaDB : Database - victor
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`victor` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `victor`;

/*Table structure for table `cache` */

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache` */

/*Table structure for table `cache_locks` */

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache_locks` */

/*Table structure for table `detail_transaksi` */

DROP TABLE IF EXISTS `detail_transaksi`;

CREATE TABLE `detail_transaksi` (
  `id_detail` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_transaksi` bigint(20) unsigned NOT NULL,
  `id_varian` bigint(20) unsigned NOT NULL,
  `jumlah` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_detail`),
  KEY `detail_transaksi_id_transaksi_foreign` (`id_transaksi`),
  KEY `detail_transaksi_id_varian_foreign` (`id_varian`),
  CONSTRAINT `detail_transaksi_id_transaksi_foreign` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE,
  CONSTRAINT `detail_transaksi_id_varian_foreign` FOREIGN KEY (`id_varian`) REFERENCES `varian_produk` (`id_varian`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `detail_transaksi` */

insert  into `detail_transaksi`(`id_detail`,`id_transaksi`,`id_varian`,`jumlah`,`subtotal`) values (1,1,1,2,2000.00),(2,4,1,2,2000.00),(3,5,1,1,1000.00),(4,6,1,1,1000.00),(5,7,1,1,1000.00),(6,8,1,1,1000.00),(7,9,1,1,1000.00),(8,10,1,1,1000.00),(9,11,1,1,1000.00),(10,12,1,1,1000.00),(11,13,1,1,1000.00),(12,14,1,1,1000.00),(13,15,1,1,1000.00),(14,16,1,1,1000.00),(15,17,1,1,1000.00),(16,18,1,1,1000.00),(17,19,1,1,1000.00);

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

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

/*Data for the table `failed_jobs` */

/*Table structure for table `job_batches` */

DROP TABLE IF EXISTS `job_batches`;

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

/*Data for the table `job_batches` */

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

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

/*Data for the table `jobs` */

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values (1,'0001_01_01_000001_create_cache_table',1),(2,'0001_01_01_000002_create_jobs_table',1),(3,'2025_10_24_004940_create_pengguna_table',1),(4,'2025_10_25_190235_create_produk_table',1),(5,'2025_10_27_080839_create_varian_produk_table',1),(6,'2025_10_27_182223_create_stok_table',1),(7,'2025_10_27_184115_create_transaksi_table',1),(8,'2025_10_27_184334_create_detailtransaksi_table',1);

/*Table structure for table `pengguna` */

DROP TABLE IF EXISTS `pengguna`;

CREATE TABLE `pengguna` (
  `id_pengguna` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(100) NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('pemilik','karyawan','kasir') NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_pengguna`),
  UNIQUE KEY `pengguna_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `pengguna` */

insert  into `pengguna`(`id_pengguna`,`nama_lengkap`,`tanggal_lahir`,`tempat_lahir`,`username`,`password`,`role`,`create_at`) values (1,'Victor Pemilik','1980-01-15','Jakarta','pemilik','$2y$12$qkSJuATIrV7w6pE.SarlCeGf6di7XRqxZWkHWl6P8dRXC5qa44z7K','pemilik','2025-12-13 10:02:39'),(2,'Budi Karyawan','1995-08-10','Surabaya','budi','$2y$12$bJ1VeAnOP/TlsG3Cp2HgMeNT1bLbkM/na59zifyCLaX0TeENXwqnW','karyawan','2025-12-13 10:02:39'),(3,'Andi Karyawan','1992-11-05','Bandung','andi','$2y$12$W/Ijc7ZLGlvXqp32ESD/nu7sT37g.vh2fZEfDwbjJch8b9OKhkFZu','karyawan','2025-12-13 10:02:39'),(4,'Siti Kasir','1998-03-25','Yogyakarta','siti','$2y$12$K7mvCPlrXEFYqW8JKmCa6eE4I6LgK0mViYu2/2UwDFqoR4QmyyqM6','kasir','2025-12-13 10:02:40'),(5,'Dewi Kasir','1999-07-18','Malang','dewi','$2y$12$RQvG5wEca3ocPM0WYSwDGeYiQkCZPCTpJXBiUEnWy9sTKmxte0j4O','kasir','2025-12-13 10:02:40');

/*Table structure for table `produk` */

DROP TABLE IF EXISTS `produk`;

CREATE TABLE `produk` (
  `id_produk` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_produk` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_produk`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `produk` */

insert  into `produk`(`id_produk`,`nama_produk`,`kategori`,`gambar`,`create_at`,`update_at`) values (1,'Kripik Udang','Snack','uploads/produk/1765620266_1.drawio.png','2025-12-13 17:04:26','2025-12-13 17:04:26');

/*Table structure for table `stok` */

DROP TABLE IF EXISTS `stok`;

CREATE TABLE `stok` (
  `id_stok` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_produk` bigint(20) unsigned NOT NULL,
  `jumlah` decimal(10,2) NOT NULL DEFAULT 0.00,
  `satuan` enum('kg','gram') NOT NULL DEFAULT 'kg',
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_stok`),
  KEY `stok_id_produk_foreign` (`id_produk`),
  CONSTRAINT `stok_id_produk_foreign` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `stok` */

insert  into `stok`(`id_stok`,`id_produk`,`jumlah`,`satuan`,`update_at`) values (1,1,10.00,'kg','2025-12-13 10:23:47');

/*Table structure for table `transaksi` */

DROP TABLE IF EXISTS `transaksi`;

CREATE TABLE `transaksi` (
  `id_transaksi` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id_midtrans` varchar(255) DEFAULT NULL,
  `id_pengguna` bigint(20) unsigned NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `metode_pembayaran` enum('tunai','kredit','debit','dompet_digital') NOT NULL,
  `tanggal_transaksi` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_transaksi` enum('berhasil','pending','gagal') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id_transaksi`),
  KEY `transaksi_id_pengguna_foreign` (`id_pengguna`),
  CONSTRAINT `transaksi_id_pengguna_foreign` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `transaksi` */

insert  into `transaksi`(`id_transaksi`,`order_id_midtrans`,`id_pengguna`,`total_harga`,`metode_pembayaran`,`tanggal_transaksi`,`status_transaksi`) values (1,'TRX-1765621574-4',4,2000.00,'dompet_digital','2025-12-13 17:26:15','berhasil'),(4,'TRX-1765622193-4',4,2000.00,'dompet_digital','2025-12-13 17:36:33','pending'),(5,'TRX-1765631499-4',4,1000.00,'dompet_digital','2025-12-13 20:11:40','pending'),(6,'TRX-1765632021-4',4,1000.00,'dompet_digital','2025-12-13 20:20:22','pending'),(7,'TRX-1765632167-4',4,1000.00,'dompet_digital','2025-12-13 20:22:48','pending'),(8,'TRX-1765632743-4',4,1000.00,'dompet_digital','2025-12-13 20:32:24','pending'),(9,'TRX-1765632935-4',4,1000.00,'dompet_digital','2025-12-13 20:35:36','pending'),(10,'TRX-1765633904-4',4,1000.00,'dompet_digital','2025-12-13 20:51:46','pending'),(11,'TRX-1765710756-4',4,1000.00,'dompet_digital','2025-12-14 18:12:40','pending'),(12,'TRX-1765710768-4',4,1000.00,'dompet_digital','2025-12-14 18:12:52','pending'),(13,'TRX-1765710796-4',4,1000.00,'dompet_digital','2025-12-14 18:13:18','pending'),(14,'TRX-1765711030-4',4,1000.00,'dompet_digital','2025-12-14 18:17:14','pending'),(15,'TRX-1765711749-4',4,1000.00,'dompet_digital','2025-12-14 18:29:12','pending'),(16,'TRX-1765712565-4',4,1000.00,'dompet_digital','2025-12-14 18:42:50','pending'),(17,'TRX-1765713096-4',4,1000.00,'dompet_digital','2025-12-14 18:51:39','pending'),(18,'TRX-1765713245-4',4,1000.00,'dompet_digital','2025-12-14 18:54:08','pending'),(19,'TRX-1765718263-4',4,1000.00,'dompet_digital','2025-12-14 20:17:45','pending');

/*Table structure for table `varian_produk` */

DROP TABLE IF EXISTS `varian_produk`;

CREATE TABLE `varian_produk` (
  `id_varian` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_produk` bigint(20) unsigned NOT NULL,
  `berat` int(11) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_varian`),
  KEY `varian_produk_id_produk_foreign` (`id_produk`),
  CONSTRAINT `varian_produk_id_produk_foreign` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `varian_produk` */

insert  into `varian_produk`(`id_varian`,`id_produk`,`berat`,`harga`) values (1,1,250,1000.00);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
