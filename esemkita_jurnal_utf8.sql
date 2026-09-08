-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: esemkita_jurnal
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
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
-- Table structure for table `dispen_siswa`
--

DROP TABLE IF EXISTS `dispen_siswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dispen_siswa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nis` varchar(10) NOT NULL,
  `keperluan` text NOT NULL,
  `tanggal` date NOT NULL,
  `jam_keluar_rencana` time DEFAULT NULL,
  `jam_kembali_rencana` time DEFAULT NULL,
  `jam_keluar_aktual` time DEFAULT NULL,
  `jam_kembali_aktual` time DEFAULT NULL,
  `status` enum('Menunggu','Disetujui','Ditolak') NOT NULL DEFAULT 'Menunggu',
  `disetujui_oleh` int DEFAULT NULL,
  `catatan_wakasis` text,
  `dicatat_satpam` int DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `dispen_nis` (`nis`),
  KEY `dispen_disetujui_oleh` (`disetujui_oleh`),
  KEY `dispen_dicatat_satpam` (`dicatat_satpam`),
  CONSTRAINT `dispen_ibfk_nis` FOREIGN KEY (`nis`) REFERENCES `siswa` (`nis`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dispen_ibfk_satpam` FOREIGN KEY (`dicatat_satpam`) REFERENCES `satpam` (`id_satpam`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `dispen_ibfk_wakasis` FOREIGN KEY (`disetujui_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dispen_siswa`
--

LOCK TABLES `dispen_siswa` WRITE;
/*!40000 ALTER TABLE `dispen_siswa` DISABLE KEYS */;
/*!40000 ALTER TABLE `dispen_siswa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
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
-- Table structure for table `foto_mengajar`
--

DROP TABLE IF EXISTS `foto_mengajar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `foto_mengajar` (
  `id_foto` int NOT NULL AUTO_INCREMENT,
  `id_jurnal` int NOT NULL,
  `foto_path` varchar(255) NOT NULL,
  `diambil_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `keterangan` text,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_foto`),
  KEY `foto_mengajar_id_jurnal` (`id_jurnal`),
  CONSTRAINT `foto_mengajar_ibfk_1` FOREIGN KEY (`id_jurnal`) REFERENCES `jurnal_mengajar` (`id_jurnal`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `foto_mengajar`
--

LOCK TABLES `foto_mengajar` WRITE;
/*!40000 ALTER TABLE `foto_mengajar` DISABLE KEYS */;
/*!40000 ALTER TABLE `foto_mengajar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guru`
--

DROP TABLE IF EXISTS `guru`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guru` (
  `id_guru` int NOT NULL AUTO_INCREMENT,
  `nip` char(18) NOT NULL,
  `nama_guru` varchar(150) NOT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `jabatan` enum('Guru','Kepala Sekolah','Wakasis Siswa','Wakasis Guru') NOT NULL DEFAULT 'Guru',
  `email` varchar(100) DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `kode_mapel` varchar(10) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_guru`),
  UNIQUE KEY `nip` (`nip`),
  UNIQUE KEY `nip_2` (`nip`),
  KEY `guru_ibfk_1` (`kode_mapel`),
  CONSTRAINT `guru_ibfk_1` FOREIGN KEY (`kode_mapel`) REFERENCES `mapel` (`kode_mapel`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guru`
--

LOCK TABLES `guru` WRITE;
/*!40000 ALTER TABLE `guru` DISABLE KEYS */;
INSERT INTO `guru` VALUES (1,'9900001','Muto\'atul Khosi\'ah, S.Pd',NULL,'Guru',NULL,NULL,'BING',NULL),(2,'9900002','Ilham Sungeidi, S.Pd',NULL,'Guru',NULL,NULL,'PJOK',NULL),(3,'9900003','Yani, S.Pd.',NULL,'Guru',NULL,NULL,'BIND',NULL),(4,'9900004','Yustin Febrini, S.Pd',NULL,'Guru',NULL,NULL,'SEJ',NULL),(5,'9900005','Anang Prasetyo, S.Pd',NULL,'Guru',NULL,NULL,'SBUD',NULL),(6,'9900006','Khuriyatul Kamila, S.Si',NULL,'Guru',NULL,NULL,'IPAS',NULL),(7,'9900007','Rifkotin Na\'imah, S.Pd',NULL,'Guru',NULL,NULL,'DTKI',NULL),(8,'9900008','Endang Ary Handayani, S.T., M.Pd',NULL,'Guru',NULL,NULL,'KKA',NULL),(9,'9900009','Sri Kusumastuti, S.Pd',NULL,'Guru',NULL,NULL,'DTKI',NULL),(10,'9900010','Muashofah, M.Pd',NULL,'Guru',NULL,NULL,'PAIBP',NULL),(11,'9900011','Lutfia Marsalina, S.Pd.I, M.Pd.',NULL,'Guru',NULL,NULL,'MTK',NULL),(12,'9900012','Arvia Rienetasary, S.Pd',NULL,'Guru',NULL,NULL,'MTK',NULL),(13,'9900013','Wiwik Yuniarsih, S.Pd',NULL,'Guru',NULL,NULL,'PPKN',NULL),(14,'9900014','Yuni Jiastuti, S.Pd',NULL,'Guru',NULL,NULL,'BK',NULL),(15,'9900015','Fajar Luthfianto, S.Pd',NULL,'Guru',NULL,NULL,'SEJ',NULL),(16,'9900016','Ista Nofasari, S.Pd',NULL,'Guru',NULL,NULL,'IPAS',NULL),(17,'9900017','Fitria Renytasari, S.Pd',NULL,'Guru',NULL,NULL,'BING',NULL),(18,'9900018','Mufatiroh, S.Ag',NULL,'Guru',NULL,NULL,'PAIBP',NULL),(19,'9900019','Winartin, S.Pd',NULL,'Guru',NULL,NULL,'BIND',NULL),(20,'9900020','Widodo, S.Pd',NULL,'Guru',NULL,NULL,'BK',NULL),(21,'9900021','Zainul Arifin, S.Pd',NULL,'Guru',NULL,NULL,'PJOK',NULL),(22,'9900022','Indriati, S.Pd',NULL,'Guru',NULL,NULL,'IPAS',NULL),(23,'9900023','Rizki Putri Wulandari, S.Pd',NULL,'Guru',NULL,NULL,'BJAW',NULL),(24,'9900024','Elysa Yuli Nur\'aini, S.Si',NULL,'Guru',NULL,NULL,'MTK',NULL),(25,'9900025','Kurnila Putri Islamawati, S.Kom',NULL,'Guru',NULL,NULL,'INF',NULL),(26,'9900026','Badrus Sulaiman, S.Pd.',NULL,'Guru',NULL,NULL,'KRPL',NULL),(27,'9900027','Ruly Dwi Setyaningrum, S.Kom',NULL,'Guru',NULL,NULL,'DPPLG',NULL),(28,'9900028','Elyana Frisca Monica, S.Pd',NULL,'Guru',NULL,NULL,'KKA',NULL),(29,'9900029','Abdul Rohman, S.Pd',NULL,'Guru',NULL,NULL,'PPKN',NULL),(30,'9900030','Umi Kulsum, S.Pd',NULL,'Guru',NULL,NULL,'BIND',NULL),(31,'9900031','Fitri Amaliyah, S.Pd',NULL,'Guru',NULL,NULL,'IPAS',NULL),(32,'9900032','Fajar Wahyu Pratiwi, S.S',NULL,'Guru',NULL,NULL,'BING',NULL),(33,'9900033','Laili Ermawati, S.Pd',NULL,'Guru',NULL,NULL,'MTK',NULL),(34,'9900034','Isti Mufadah, S.Pd',NULL,'Guru',NULL,NULL,'BING',NULL),(35,'9900035','Listyana Hartati, S.Kom., M.Pd',NULL,'Guru',NULL,NULL,'KKA',NULL),(36,'9900036','Siswanti Purwaningsih, S.T., M.Pd',NULL,'Guru',NULL,NULL,'INF',NULL),(37,'9900037','Muhammad Fajar Assidiqi, S.Pd',NULL,'Guru',NULL,NULL,'BJAW',NULL),(38,'9900038','Nishfu Laili, S.Pd',NULL,'Guru',NULL,NULL,'BK',NULL),(39,'9900039','Basuki Sarjono, S.Pd',NULL,'Guru',NULL,NULL,'MTK',NULL),(40,'9900040','Sri Rahayu, S.Pd',NULL,'Guru',NULL,NULL,'BIND',NULL),(41,'9900041','Siti Munawaroh, S.Kom.,M.Pd',NULL,'Guru',NULL,NULL,'KKA',NULL),(42,'9900042','Sinta Lestari, S.Pd.I',NULL,'Guru',NULL,NULL,'PAIBP',NULL),(43,'9900043','Pipit Ambarwati, S.Pd',NULL,'Guru',NULL,NULL,'DAKL',NULL),(44,'9900044','Ratih Dian Irawati, SE',NULL,'Guru',NULL,NULL,'DPM',NULL),(45,'9900045','Arif Setyobudi, S.Pd',NULL,'Guru',NULL,NULL,'BIND',NULL),(46,'9900046','Dian Mawarti, S.Pd',NULL,'Guru',NULL,NULL,'BK',NULL),(47,'9900047','Anisa Kusumawati, S.Pd',NULL,'Guru',NULL,NULL,'KIK',NULL),(48,'9900048','Angga Widhy Wirawan, S.Pd.,M.Pd',NULL,'Guru',NULL,NULL,'SBUD',NULL),(49,'9900049','Agus Fahruddy, S.Pd., M.Pd',NULL,'Guru',NULL,NULL,'PJOK',NULL),(50,'9900050','Eko Saputro, S.Pd',NULL,'Guru',NULL,NULL,'MTK',NULL),(51,'9900051','Retno Widyastuti, S.Pd., M.Pd',NULL,'Guru',NULL,NULL,'DPM',NULL),(52,'9900052','Erna Qoriah, S.E.',NULL,'Guru',NULL,NULL,'KIK',NULL),(53,'9900053','Dwi Rini Manfaati, S.Pd',NULL,'Guru',NULL,NULL,'BING',NULL),(54,'9900054','Fitria Diah Ayu Hartati, S.Pd',NULL,'Guru',NULL,NULL,'PAIBP',NULL),(55,'9900055','Bella Prakoso, S.Pd',NULL,'Guru',NULL,NULL,'PJOK',NULL),(56,'9900056','Sri Subekti, S.Pd',NULL,'Guru',NULL,NULL,'IPAS',NULL),(57,'9900057','Siti Khoiriyah, S.Pd',NULL,'Guru',NULL,NULL,'BIND',NULL),(58,'9900058','Niken Hari Isnaini, S.Pd',NULL,'Guru',NULL,NULL,'BK',NULL),(59,'9900059','Purwati, S.Pd',NULL,'Guru',NULL,NULL,'BIND',NULL),(60,'9900060','Dwi Kuswanto, S.Pd',NULL,'Guru',NULL,NULL,'DMPLB',NULL),(61,'9900061','Komariyah, S.Pd',NULL,'Guru',NULL,NULL,'BING',NULL),(62,'9900062','Rulik Indrawati, S.Pd',NULL,'Guru',NULL,NULL,'MTK',NULL),(63,'9900063','Veronica Damay Pristiani, S.Pd',NULL,'Guru',NULL,NULL,'BK',NULL),(64,'9900064','Tutut Sriatin, S.Pd',NULL,'Guru',NULL,NULL,'MPMP',NULL),(65,'9900065','Kurnila Putri Islamawati, S.Pd',NULL,'Guru',NULL,NULL,'KRPL',NULL),(66,'9900066','Peni Wulandari, S.Pd',NULL,'Guru',NULL,NULL,'DMPLB',NULL),(67,'9900067','Ary Sunaryo, S.T., M.Pd',NULL,'Guru',NULL,NULL,'INF',NULL),(68,'9900068','Lilik Suratmi, S.Pd',NULL,'Guru',NULL,NULL,'DMPLB',NULL),(69,'9900069','Nur Eko Wahyudi, S.Pd',NULL,'Guru',NULL,NULL,'BK',NULL),(70,'9900070','Rindang Rejeki, S.Pd',NULL,'Guru',NULL,NULL,'DMPLB',NULL),(71,'9900071','Siti Maisaroh, S.Pd',NULL,'Guru',NULL,NULL,'BK',NULL),(72,'9900072','Alfinu Farikh Abdillah, S.Pd.I',NULL,'Guru',NULL,NULL,'PAIBP',NULL),(73,'9900073','Shinta Indyar Shanty Susanto, S.Kom',NULL,'Guru',NULL,NULL,'INF',NULL),(74,'9900074','Endang Safitri, S.Pd',NULL,'Guru',NULL,NULL,'BIND',NULL),(75,'9900075','Atih Wilupi, S.E., M.Pd',NULL,'Guru',NULL,NULL,'KAK',NULL),(76,'9900076','Yuli Ratnasari, S.Pd',NULL,'Guru',NULL,NULL,'MPAK',NULL),(77,'9900077','Agustina Mardika Rini, S.Pd.,M.Pd',NULL,'Guru',NULL,NULL,'DAKL',NULL),(78,'9900078','Indayah, S.Pd., M.Pd',NULL,'Guru',NULL,NULL,'KAK',NULL),(79,'9900079','Andri Retno Yuli Astuti, S.Pd',NULL,'Guru',NULL,NULL,'BING',NULL),(80,'9900080','Dwi Nova Setyandari, S.Pd',NULL,'Guru',NULL,NULL,'MTK',NULL),(81,'9900081','Astra Bella Flamboyan, S.Psi',NULL,'Guru',NULL,NULL,'BK',NULL),(82,'9900082','Titin Sukmasari, S.Pd., M.Pd',NULL,'Guru',NULL,NULL,'DAKL',NULL),(83,'9900083','Setiyo Winarko, S.Pd',NULL,'Guru',NULL,NULL,'DAKL',NULL),(84,'9900084','Septiani, S.Pd.,M.Pd',NULL,'Guru',NULL,NULL,'DAKL',NULL),(85,'9900085','Risqi Nur Imama, S.Tr.Par',NULL,'Guru',NULL,NULL,'DULP',NULL),(86,'9900086','Dra. Hanik Pangestuti',NULL,'Guru',NULL,NULL,'PAIBP',NULL),(87,'9900087','Danang Anjar Hymawanto, S.Pd',NULL,'Guru',NULL,NULL,'KDKV',NULL),(88,'9900088','Agus Pramono, S.Sn',NULL,'Guru',NULL,NULL,'DDKV',NULL),(89,'9900089','Khoyrotun Hisani, S.Sn',NULL,'Guru',NULL,NULL,'BJAW',NULL),(90,'9900090','Erna Rinawati, S.Pd',NULL,'Guru',NULL,NULL,'BIND',NULL),(91,'9900091','Endik Kuswantoro, S.Kom., M.T',NULL,'Guru',NULL,NULL,'KKA',NULL),(92,'9900092','Ajeng Okvitasari, S.Pd',NULL,'Guru',NULL,NULL,'MTK',NULL),(93,'9900093','Benny Mamora, S.Kom',NULL,'Guru',NULL,NULL,'DBP',NULL),(94,'9900094','Winarsih, S.Pd, M.Pd',NULL,'Guru',NULL,NULL,'BK',NULL),(95,'9900095','Sa\'ad Wazis Hiedayat, S.Pd',NULL,'Guru',NULL,NULL,'KPSPT',NULL),(96,'9900096','Agus Muharyanto, M.Pd',NULL,'Guru',NULL,NULL,'BING',NULL),(97,'9900097','Tuhu Eries Kudori, S.Sn',NULL,'Guru',NULL,NULL,'INF',NULL),(98,'9900098','Rika Okta Maulida, S.Ds.',NULL,'Guru',NULL,NULL,'DAN',NULL),(99,'9900099','Dhuana Putri Puspitasary, S.Pd',NULL,'Guru',NULL,NULL,'DAN',NULL),(100,'9900100','Mega Mahardika, S.Pd',NULL,'Guru',NULL,NULL,'KMP',NULL),(101,'9900101','Baskoro, S.Si',NULL,'Guru',NULL,NULL,'KIK',NULL),(102,'9900102','Ayu Puspitorini, ST',NULL,'Guru',NULL,NULL,'KTKI',NULL),(103,'9900103','Diana Hartanti, S.T., M.Pd',NULL,'Guru',NULL,NULL,'KTKI',NULL),(104,'9900104','Hendro Suwignyo, ST',NULL,'Guru',NULL,NULL,'MPRPL',NULL),(105,'9900105','Sulistyowati, SS',NULL,'Guru',NULL,NULL,'BJPN',NULL),(106,'9900106','Niken Dewi Hastika, S.Pd',NULL,'Guru',NULL,NULL,'KBD',NULL),(107,'9900107','Luluk Munfarida, S.Pd',NULL,'Guru',NULL,NULL,'KBD',NULL),(108,'9900108','Listyana Hartati, S.Kom.,M.Pd',NULL,'Guru',NULL,NULL,'KTKJ',NULL),(109,'9900109','Andri Krisdianto, SE.,M.Pd',NULL,'Guru',NULL,NULL,'MPBD',NULL),(110,'9900110','Nurul Azizah, S.Pd',NULL,'Guru',NULL,NULL,'KBD',NULL),(111,'9900111','Nur Eko Wahyuningsih, S.Pd',NULL,'Guru',NULL,NULL,'BK',NULL),(112,'9900112','Andri Krisdianto, SE., M.Pd',NULL,'Guru',NULL,NULL,'KBD',NULL),(113,'9900113','Agung Yulianto, S.Pd',NULL,'Guru',NULL,NULL,'KBD',NULL),(114,'9900114','Dra. Anik Indriani',NULL,'Guru',NULL,NULL,'SEJ',NULL),(115,'9900115','Martiin, S.Pd',NULL,'Guru',NULL,NULL,'KMP',NULL),(116,'9900116','Dra. Susakti Yuharini',NULL,'Guru',NULL,NULL,'KIK',NULL),(117,'9900117','Veronica Damay Rulitasari, S.Pd',NULL,'Guru',NULL,NULL,'BK',NULL),(118,'9900118','Sunarti, S.Pd',NULL,'Guru',NULL,NULL,'KMP',NULL),(119,'9900119','Titik Samsistini, S.Pd',NULL,'Guru',NULL,NULL,'KMP',NULL),(120,'9900120','Kasmi, S.Pd., M.Pd',NULL,'Guru',NULL,NULL,'KAK',NULL),(121,'9900121','Dyah Esti Rahayu, S.Pd',NULL,'Guru',NULL,NULL,'KAK',NULL),(122,'9900122','Ninik Sriwidayati, S.Pd., M.Pd',NULL,'Guru',NULL,NULL,'KIK',NULL),(123,'9900123','Nur Nastutisari, S.ST.Par.',NULL,'Guru',NULL,NULL,'KIK',NULL),(124,'9900124','Niken Hari Pratiwi, S.Psi., M.Pd',NULL,'Guru',NULL,NULL,'BK',NULL),(125,'9900125','Istiana Suhartati, S.T',NULL,'Guru',NULL,NULL,'KDKV',NULL),(126,'9900126','Mas\'an Widodo, S.Pd. M.T',NULL,'Guru',NULL,NULL,'KIK',NULL),(127,'9900127','Joko Priyanto, S.Kom',NULL,'Guru',NULL,NULL,'KPSPT',NULL),(128,'9900128','Siti Umiharsih, S.Pd',NULL,'Guru',NULL,NULL,'KIK',NULL),(129,'9900129','Andika Christian Sasmita, S.ST',NULL,'Guru',NULL,NULL,'KIK',NULL),(130,'9900130','Erwan Septiyono, S.Pd',NULL,'Guru',NULL,NULL,'KAN',NULL);
/*!40000 ALTER TABLE `guru` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guru_piket`
--

DROP TABLE IF EXISTS `guru_piket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guru_piket` (
  `id_piket` int NOT NULL AUTO_INCREMENT,
  `id_guru` int NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat') DEFAULT NULL,
  `tanggal_khusus` date DEFAULT NULL,
  `shift` enum('Pagi','Siang') NOT NULL DEFAULT 'Pagi',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_piket`),
  KEY `guru_piket_id_guru` (`id_guru`),
  CONSTRAINT `guru_piket_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guru_piket`
--

LOCK TABLES `guru_piket` WRITE;
/*!40000 ALTER TABLE `guru_piket` DISABLE KEYS */;
/*!40000 ALTER TABLE `guru_piket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jadwal`
--

DROP TABLE IF EXISTS `jadwal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jadwal` (
  `id_jadwal` int NOT NULL AUTO_INCREMENT,
  `id_kelas` int NOT NULL,
  `id_guru` int NOT NULL,
  `id_ruangan` int NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat') NOT NULL,
  `jam_mulai` int NOT NULL,
  `jam_selesai` int NOT NULL,
  `kode_mapel` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_jadwal`),
  UNIQUE KEY `id_kelas` (`id_kelas`,`hari`,`jam_mulai`),
  UNIQUE KEY `id_guru` (`id_guru`,`hari`,`jam_mulai`),
  KEY `id_ruangan` (`id_ruangan`),
  KEY `idx_jadwal_guru_hari` (`id_guru`,`hari`),
  KEY `idx_jadwal_kelas_hari` (`id_kelas`,`hari`),
  KEY `jadwal_ibfk_3` (`kode_mapel`),
  CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `jadwal_ibfk_2` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `jadwal_ibfk_3` FOREIGN KEY (`kode_mapel`) REFERENCES `mapel` (`kode_mapel`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `jadwal_ibfk_4` FOREIGN KEY (`id_ruangan`) REFERENCES `ruangan` (`id_ruangan`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=971 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jadwal`
--

LOCK TABLES `jadwal` WRITE;
/*!40000 ALTER TABLE `jadwal` DISABLE KEYS */;
INSERT INTO `jadwal` VALUES (1,1,1,1,'Senin',2,3,'BING',NULL),(2,1,2,2,'Senin',4,6,'PJOK',NULL),(3,1,3,1,'Senin',7,8,'BIND',NULL),(4,1,4,1,'Senin',9,10,'BJAW',NULL),(5,1,5,1,'Selasa',1,2,'SBUD',NULL),(6,1,3,1,'Selasa',3,4,'BIND',NULL),(7,1,6,1,'Selasa',5,7,'IPAS',NULL),(8,1,7,1,'Selasa',8,10,'DTKI',NULL),(9,1,8,3,'Rabu',1,2,'KKA',NULL),(10,1,9,1,'Rabu',3,5,'DTKI',NULL),(11,1,10,1,'Rabu',6,8,'PAIBP',NULL),(12,1,11,4,'Rabu',9,10,'INF',NULL),(13,1,12,1,'Kamis',1,2,'MTK',NULL),(14,1,13,1,'Kamis',3,4,'PPKN',NULL),(15,1,6,1,'Kamis',5,7,'IPAS',NULL),(16,1,9,1,'Kamis',8,10,'DTKI',NULL),(17,1,7,1,'Jumat',2,4,'DTKI',NULL),(18,1,14,1,'Jumat',5,5,'BK',NULL),(19,1,12,1,'Jumat',6,7,'MTK',NULL),(20,1,11,4,'Jumat',8,9,'INF',NULL),(21,1,15,1,'Jumat',10,11,'SEJ',NULL),(22,1,1,1,'Jumat',12,13,'BING',NULL),(23,2,3,5,'Senin',2,3,'BIND',NULL),(24,2,12,5,'Senin',4,5,'MTK',NULL),(25,2,13,5,'Senin',6,7,'PPKN',NULL),(26,2,9,5,'Senin',8,10,'DTKI',NULL),(27,2,14,5,'Selasa',1,1,'BK',NULL),(28,2,7,5,'Selasa',2,4,'DTKI',NULL),(29,2,11,4,'Selasa',5,6,'INF',NULL),(30,2,16,5,'Selasa',7,8,'SEJ',NULL),(31,2,8,3,'Selasa',9,10,'KKA',NULL),(32,2,12,5,'Rabu',1,2,'MTK',NULL),(33,2,5,5,'Rabu',3,4,'SBUD',NULL),(34,2,17,5,'Rabu',5,6,'BING',NULL),(35,2,11,4,'Rabu',7,8,'INF',NULL),(36,2,4,5,'Rabu',9,10,'BJAW',NULL),(37,2,7,5,'Kamis',1,3,'DTKI',NULL),(38,2,17,5,'Kamis',4,5,'BING',NULL),(39,2,3,5,'Kamis',6,7,'BIND',NULL),(40,2,6,5,'Kamis',8,10,'IPAS',NULL),(41,2,18,5,'Jumat',2,4,'PAIBP',NULL),(42,2,2,2,'Jumat',5,7,'PJOK',NULL),(43,2,9,5,'Jumat',8,10,'DTKI',NULL),(44,2,6,5,'Jumat',11,13,'IPAS',NULL),(45,3,19,6,'Senin',2,3,'BIND',NULL),(46,3,20,6,'Senin',4,4,'BK',NULL),(47,3,21,2,'Senin',5,7,'PJOK',NULL),(48,3,22,7,'Senin',8,10,'IPAS',NULL),(49,3,22,7,'Selasa',1,3,'IPAS',NULL),(50,3,23,7,'Selasa',4,5,'BJAW',NULL),(51,3,17,7,'Selasa',6,7,'BING',NULL),(52,3,18,7,'Selasa',8,10,'PAIBP',NULL),(53,3,5,7,'Rabu',1,2,'SBUD',NULL),(54,3,24,7,'Rabu',3,4,'MTK',NULL),(55,3,25,6,'Rabu',5,6,'INF',NULL),(56,3,26,6,'Rabu',7,10,'DPPLG',NULL),(57,3,17,7,'Kamis',1,2,'BING',NULL),(58,3,24,7,'Kamis',3,4,'MTK',NULL),(59,3,4,7,'Kamis',5,6,'SEJ',NULL),(60,3,27,6,'Kamis',7,10,'DPPLG',NULL),(61,3,27,6,'Jumat',2,5,'DPPLG',NULL),(62,3,28,6,'Jumat',6,7,'KKA',NULL),(63,3,29,7,'Jumat',8,9,'PPKN',NULL),(64,3,25,6,'Jumat',10,11,'INF',NULL),(65,3,19,6,'Jumat',12,13,'BIND',NULL),(66,4,27,8,'Senin',2,5,'DPPLG',NULL),(67,4,30,7,'Senin',6,7,'BIND',NULL),(68,4,31,9,'Senin',8,10,'IPAS',NULL),(69,4,28,8,'Selasa',1,4,'INF',NULL),(70,4,28,8,'Selasa',5,6,'KKA',NULL),(71,4,4,9,'Selasa',7,8,'SEJ',NULL),(72,4,29,9,'Selasa',9,10,'PPKN',NULL),(73,4,32,9,'Rabu',1,2,'BING',NULL),(74,4,32,9,'Rabu',3,4,'BING',NULL),(75,4,24,9,'Rabu',5,6,'MTK',NULL),(76,4,27,8,'Rabu',7,10,'DPPLG',NULL),(77,4,26,8,'Kamis',1,4,'DPPLG',NULL),(78,4,18,9,'Kamis',5,7,'PAIBP',NULL),(79,4,31,9,'Kamis',8,10,'IPAS',NULL),(80,4,30,8,'Jumat',2,3,'BIND',NULL),(81,4,20,8,'Jumat',4,4,'BK',NULL),(82,4,21,2,'Jumat',5,7,'PJOK',NULL),(83,4,33,9,'Jumat',8,9,'BJAW',NULL),(84,4,5,9,'Jumat',10,11,'SBUD',NULL),(85,4,24,9,'Jumat',12,13,'MTK',NULL),(86,5,31,10,'Senin',2,4,'IPAS',NULL),(87,5,34,10,'Senin',5,6,'BING',NULL),(88,5,35,3,'Senin',7,8,'KKA',NULL),(89,5,35,3,'Senin',9,10,'DTJKT',NULL),(90,5,36,3,'Selasa',1,4,'INF',NULL),(91,5,31,10,'Selasa',5,7,'IPAS',NULL),(92,5,37,10,'Selasa',8,9,'BJAW',NULL),(93,5,38,10,'Selasa',10,10,'BK',NULL),(94,5,39,10,'Rabu',1,2,'MTK',NULL),(95,5,40,10,'Rabu',3,4,'BIND',NULL),(96,5,8,3,'Rabu',5,6,'DTJKT',NULL),(97,5,39,10,'Rabu',7,8,'MTK',NULL),(98,5,34,10,'Rabu',9,10,'BING',NULL),(99,5,16,10,'Kamis',1,2,'SEJ',NULL),(100,5,40,10,'Kamis',3,4,'BIND',NULL),(101,5,13,10,'Kamis',5,6,'PPKN',NULL),(102,5,41,3,'Kamis',7,10,'DTJKT',NULL),(103,5,2,2,'Jumat',2,4,'PJOK',NULL),(104,5,5,10,'Jumat',5,6,'SBUD',NULL),(105,5,36,3,'Jumat',7,10,'DTJKT',NULL),(106,5,42,10,'Jumat',11,13,'PAIBP',NULL),(107,6,41,3,'Senin',2,5,'DTJKT',NULL),(108,6,42,11,'Senin',6,8,'PAIBP',NULL),(109,6,39,11,'Senin',9,10,'MTK',NULL),(110,6,40,11,'Selasa',1,2,'BIND',NULL),(111,6,2,2,'Selasa',3,5,'PJOK',NULL),(112,6,8,3,'Selasa',6,7,'DTJKT',NULL),(113,6,31,11,'Selasa',8,10,'IPAS',NULL),(114,6,13,11,'Rabu',1,2,'PPKN',NULL),(115,6,38,11,'Rabu',3,3,'BK',NULL),(116,6,31,11,'Rabu',4,6,'IPAS',NULL),(117,6,35,3,'Rabu',7,8,'DTJKT',NULL),(118,6,39,11,'Rabu',9,10,'MTK',NULL),(119,6,36,3,'Kamis',1,4,'INF',NULL),(120,6,40,11,'Kamis',5,6,'BIND',NULL),(121,6,5,11,'Kamis',7,8,'SBUD',NULL),(122,6,34,11,'Kamis',9,10,'BING',NULL),(123,6,36,3,'Jumat',2,5,'DTJKT',NULL),(124,6,4,11,'Jumat',6,7,'SEJ',NULL),(125,6,37,11,'Jumat',8,9,'BJAW',NULL),(126,6,34,11,'Jumat',10,11,'BING',NULL),(127,6,35,3,'Jumat',12,13,'KKA',NULL),(128,7,43,12,'Senin',2,3,'SEJ',NULL),(129,7,44,12,'Senin',4,7,'DPM',NULL),(130,7,45,12,'Senin',8,9,'BIND',NULL),(131,7,46,12,'Senin',10,10,'BK',NULL),(132,7,42,12,'Selasa',1,3,'PAIBP',NULL),(133,7,45,12,'Selasa',4,5,'BIND',NULL),(134,7,22,12,'Selasa',6,8,'IPAS',NULL),(135,7,33,12,'Selasa',9,10,'MTK',NULL),(136,7,41,13,'Rabu',1,2,'KKA',NULL),(137,7,1,12,'Rabu',3,4,'BING',NULL),(138,7,47,12,'Rabu',5,8,'DPM',NULL),(139,7,48,12,'Rabu',9,10,'SBUD',NULL),(140,7,22,12,'Kamis',1,3,'IPAS',NULL),(141,7,49,2,'Kamis',4,6,'PJOK',NULL),(142,7,1,12,'Kamis',7,8,'BING',NULL),(143,7,47,12,'Kamis',9,10,'PPKN',NULL),(144,7,50,13,'Jumat',2,5,'INF',NULL),(145,7,33,12,'Jumat',6,7,'MTK',NULL),(146,7,51,12,'Jumat',8,11,'DPM',NULL),(147,7,23,12,'Jumat',12,13,'BJAW',NULL),(148,8,22,14,'Senin',2,4,'IPAS',NULL),(149,8,51,14,'Senin',5,8,'DPM',NULL),(150,8,47,14,'Senin',9,10,'PPKN',NULL),(151,8,52,14,'Selasa',1,2,'BJAW',NULL),(152,8,41,13,'Selasa',3,4,'KKA',NULL),(153,8,33,14,'Selasa',5,6,'MTK',NULL),(154,8,44,14,'Selasa',7,10,'DPM',NULL),(155,8,42,14,'Rabu',1,3,'PAIBP',NULL),(156,8,22,14,'Rabu',4,6,'IPAS',NULL),(157,8,50,13,'Rabu',7,10,'INF',NULL),(158,8,47,14,'Kamis',1,4,'DPM',NULL),(159,8,53,14,'Kamis',5,6,'BING',NULL),(160,8,43,14,'Kamis',7,8,'SEJ',NULL),(161,8,45,14,'Kamis',9,10,'BIND',NULL),(162,8,46,14,'Jumat',2,2,'BK',NULL),(163,8,53,14,'Jumat',3,4,'BING',NULL),(164,8,49,2,'Jumat',5,7,'PJOK',NULL),(165,8,45,14,'Jumat',8,9,'BIND',NULL),(166,8,48,14,'Jumat',10,11,'SBUD',NULL),(167,8,33,14,'Jumat',12,13,'MTK',NULL),(168,9,54,15,'Senin',2,4,'PAIBP',NULL),(169,9,55,2,'Senin',5,7,'PJOK',NULL),(170,9,56,15,'Senin',8,10,'IPAS',NULL),(171,9,50,15,'Selasa',1,2,'MTK',NULL),(172,9,51,15,'Selasa',3,6,'DPM',NULL),(173,9,53,15,'Selasa',7,8,'BING',NULL),(174,9,57,15,'Selasa',9,10,'BIND',NULL),(175,9,47,15,'Rabu',1,4,'DPM',NULL),(176,9,4,15,'Rabu',5,6,'SEJ',NULL),(177,9,37,15,'Rabu',7,8,'BJAW',NULL),(178,9,57,15,'Rabu',9,10,'BIND',NULL),(179,9,29,15,'Kamis',1,2,'PPKN',NULL),(180,9,56,15,'Kamis',3,5,'IPAS',NULL),(181,9,48,15,'Kamis',6,7,'SBUD',NULL),(182,9,53,15,'Kamis',8,9,'BING',NULL),(183,9,58,15,'Kamis',10,10,'BK',NULL),(184,9,44,15,'Jumat',2,5,'DPM',NULL),(185,9,50,13,'Jumat',6,9,'INF',NULL),(186,9,50,15,'Jumat',10,11,'MTK',NULL),(187,9,41,13,'Jumat',12,13,'KKA',NULL),(188,10,56,16,'Senin',2,4,'IPAS',NULL),(189,10,15,16,'Senin',5,6,'SEJ',NULL),(190,10,48,16,'Senin',7,8,'SBUD',NULL),(191,10,59,16,'Senin',9,10,'BIND',NULL),(192,10,60,16,'Selasa',1,4,'DMPLB',NULL),(193,10,61,16,'Selasa',5,6,'BING',NULL),(194,10,59,16,'Selasa',7,8,'BIND',NULL),(195,10,62,16,'Selasa',9,10,'MTK',NULL),(196,10,61,16,'Rabu',1,2,'BING',NULL),(197,10,37,16,'Rabu',3,4,'BJAW',NULL),(198,10,56,16,'Rabu',5,7,'IPAS',NULL),(199,10,63,16,'Rabu',8,8,'BK',NULL),(200,10,25,17,'Rabu',9,10,'INF',NULL),(201,10,64,16,'Kamis',1,2,'PPKN',NULL),(202,10,62,16,'Kamis',3,4,'MTK',NULL),(203,10,65,17,'Kamis',5,6,'KKA',NULL),(204,10,60,16,'Kamis',7,10,'DMPLB',NULL),(205,10,21,2,'Jumat',2,4,'PJOK',NULL),(206,10,60,16,'Jumat',5,8,'DMPLB',NULL),(207,10,54,16,'Jumat',9,11,'PAIBP',NULL),(208,10,25,17,'Jumat',12,13,'INF',NULL),(209,11,38,18,'Senin',2,2,'BK',NULL),(210,11,64,18,'Senin',3,4,'PPKN',NULL),(211,11,37,18,'Senin',5,6,'BJAW',NULL),(212,11,61,18,'Senin',7,8,'BING',NULL),(213,11,15,18,'Senin',9,10,'SEJ',NULL),(214,11,61,18,'Selasa',1,2,'BING',NULL),(215,11,62,18,'Selasa',3,4,'MTK',NULL),(216,11,66,18,'Selasa',5,8,'DMPLB',NULL),(217,11,65,17,'Selasa',9,10,'KKA',NULL),(218,11,48,18,'Rabu',1,2,'SBUD',NULL),(219,11,21,2,'Rabu',3,5,'PJOK',NULL),(220,11,62,18,'Rabu',6,7,'MTK',NULL),(221,11,56,18,'Rabu',8,10,'IPAS',NULL),(222,11,67,17,'Kamis',1,4,'INF',NULL),(223,11,59,18,'Kamis',5,6,'BIND',NULL),(224,11,66,18,'Kamis',7,10,'DMPLB',NULL),(225,11,56,18,'Jumat',2,4,'IPAS',NULL),(226,11,54,18,'Jumat',5,7,'PAIBP',NULL),(227,11,59,18,'Jumat',8,9,'BIND',NULL),(228,11,66,18,'Jumat',10,13,'DMPLB',NULL),(229,12,21,2,'Senin',2,4,'PJOK',NULL),(230,12,61,19,'Senin',5,6,'BING',NULL),(231,12,68,19,'Senin',7,10,'DMPLB',NULL),(232,12,45,19,'Selasa',1,2,'BIND',NULL),(233,12,61,19,'Selasa',3,4,'BING',NULL),(234,12,26,20,'Selasa',5,6,'KKA',NULL),(235,12,68,19,'Selasa',7,10,'DMPLB',NULL),(236,12,45,19,'Rabu',1,2,'BIND',NULL),(237,12,39,19,'Rabu',3,4,'MTK',NULL),(238,12,6,19,'Rabu',5,7,'IPAS',NULL),(239,12,54,19,'Rabu',8,10,'PAIBP',NULL),(240,12,6,19,'Kamis',1,3,'IPAS',NULL),(241,12,39,19,'Kamis',4,5,'MTK',NULL),(242,12,69,19,'Kamis',6,6,'BK',NULL),(243,12,26,20,'Kamis',7,10,'INF',NULL),(244,12,6,19,'Jumat',2,3,'PPKN',NULL),(245,12,4,19,'Jumat',4,5,'SEJ',NULL),(246,12,23,19,'Jumat',6,7,'BJAW',NULL),(247,12,68,19,'Jumat',8,11,'DMPLB',NULL),(248,12,48,19,'Jumat',12,13,'SBUD',NULL),(249,13,6,21,'Senin',2,4,'IPAS',NULL),(250,13,70,21,'Senin',5,8,'DMPLB',NULL),(251,13,50,21,'Senin',9,10,'MTK',NULL),(252,13,21,2,'Selasa',1,3,'PJOK',NULL),(253,13,1,21,'Selasa',4,5,'BING',NULL),(254,13,45,21,'Selasa',6,7,'BIND',NULL),(255,13,6,21,'Selasa',8,10,'IPAS',NULL),(256,13,26,20,'Rabu',1,2,'KKA',NULL),(257,13,70,21,'Rabu',3,6,'DMPLB',NULL),(258,13,45,21,'Rabu',7,8,'BIND',NULL),(259,13,15,21,'Rabu',9,10,'SEJ',NULL),(260,13,1,21,'Kamis',1,2,'BING',NULL),(261,13,71,21,'Kamis',3,3,'BK',NULL),(262,13,72,21,'Kamis',4,6,'PAIBP',NULL),(263,13,50,21,'Kamis',7,8,'MTK',NULL),(264,13,48,21,'Kamis',9,10,'SBUD',NULL),(265,13,26,20,'Jumat',2,5,'INF',NULL),(266,13,29,21,'Jumat',6,7,'PPKN',NULL),(267,13,23,21,'Jumat',8,9,'BJAW',NULL),(268,13,70,21,'Jumat',10,13,'DMPLB',NULL),(269,14,11,22,'Senin',2,3,'MTK',NULL),(270,14,17,22,'Senin',4,5,'BING',NULL),(271,14,69,22,'Senin',6,6,'BK',NULL),(272,14,15,22,'Senin',7,8,'SEJ',NULL),(273,14,73,23,'Senin',9,10,'INF',NULL),(274,14,48,22,'Selasa',1,2,'SBUD',NULL),(275,14,43,22,'Selasa',3,5,'DAKL',NULL),(276,14,56,22,'Selasa',6,8,'IPAS',NULL),(277,14,73,23,'Selasa',9,10,'INF',NULL),(278,14,17,22,'Rabu',1,2,'BING',NULL),(279,14,49,2,'Rabu',3,5,'PJOK',NULL),(280,14,74,22,'Rabu',6,7,'BIND',NULL),(281,14,43,22,'Rabu',8,10,'DAKL',NULL),(282,14,74,22,'Kamis',1,2,'BIND',NULL),(283,14,31,22,'Kamis',3,4,'BJAW',NULL),(284,14,75,22,'Kamis',5,6,'DAKL',NULL),(285,14,76,22,'Kamis',7,8,'PPKN',NULL),(286,14,11,22,'Kamis',9,10,'MTK',NULL),(287,14,73,23,'Jumat',2,3,'KKA',NULL),(288,14,72,22,'Jumat',4,6,'PAIBP',NULL),(289,14,56,22,'Jumat',7,9,'IPAS',NULL),(290,14,77,24,'Jumat',10,13,'DAKL',NULL),(291,15,17,25,'Senin',2,3,'BING',NULL),(292,15,78,25,'Senin',4,6,'DAKL',NULL),(293,15,73,23,'Senin',7,8,'INF',NULL),(294,15,74,25,'Senin',9,10,'BIND',NULL),(295,15,73,23,'Selasa',1,2,'INF',NULL),(296,15,17,25,'Selasa',3,4,'BING',NULL),(297,15,49,2,'Selasa',5,7,'PJOK',NULL),(298,15,11,25,'Selasa',8,9,'MTK',NULL),(299,15,69,25,'Selasa',10,10,'BK',NULL),(300,15,56,25,'Rabu',1,3,'IPAS',NULL),(301,15,72,25,'Rabu',4,6,'PAIBP',NULL),(302,15,77,24,'Rabu',7,10,'DAKL',NULL),(303,15,78,25,'Kamis',1,3,'DAKL',NULL),(304,15,11,25,'Kamis',4,5,'MTK',NULL),(305,15,56,25,'Kamis',6,8,'IPAS',NULL),(306,15,73,23,'Kamis',9,10,'KKA',NULL),(307,15,76,25,'Jumat',2,3,'PPKN',NULL),(308,15,74,25,'Jumat',4,5,'BIND',NULL),(309,15,31,25,'Jumat',6,7,'BJAW',NULL),(310,15,48,25,'Jumat',8,9,'SBUD',NULL),(311,15,43,25,'Jumat',10,11,'DAKL',NULL),(312,15,15,25,'Jumat',12,13,'SEJ',NULL),(313,16,49,2,'Senin',2,4,'PJOK',NULL),(314,16,43,26,'Senin',5,6,'DAKL',NULL),(315,16,27,24,'Senin',7,8,'KKA',NULL),(316,16,67,24,'Senin',9,10,'INF',NULL),(317,16,67,24,'Selasa',1,2,'INF',NULL),(318,16,48,26,'Selasa',3,4,'SBUD',NULL),(319,16,29,26,'Selasa',5,6,'PPKN',NULL),(320,16,74,26,'Selasa',7,8,'BIND',NULL),(321,16,79,26,'Selasa',9,10,'BING',NULL),(322,16,72,26,'Rabu',1,3,'PAIBP',NULL),(323,16,80,26,'Rabu',4,5,'MTK',NULL),(324,16,81,26,'Rabu',6,6,'BK',NULL),(325,16,82,23,'Rabu',7,10,'DAKL',NULL),(326,16,43,26,'Kamis',1,2,'SEJ',NULL),(327,16,23,26,'Kamis',3,4,'BJAW',NULL),(328,16,83,26,'Kamis',5,7,'DAKL',NULL),(329,16,16,26,'Kamis',8,10,'IPAS',NULL),(330,16,16,26,'Jumat',2,4,'IPAS',NULL),(331,16,79,26,'Jumat',5,6,'BING',NULL),(332,16,83,26,'Jumat',7,9,'DAKL',NULL),(333,16,80,26,'Jumat',10,11,'MTK',NULL),(334,16,74,26,'Jumat',12,13,'BIND',NULL),(335,17,48,27,'Senin',2,3,'SBUD',NULL),(336,17,81,27,'Senin',4,4,'BK',NULL),(337,17,16,27,'Senin',5,7,'IPAS',NULL),(338,17,10,27,'Senin',8,10,'PAIBP',NULL),(339,17,43,27,'Selasa',1,2,'SEJ',NULL),(340,17,67,24,'Selasa',3,4,'INF',NULL),(341,17,80,27,'Selasa',5,6,'MTK',NULL),(342,17,79,27,'Selasa',7,8,'BING',NULL),(343,17,43,27,'Selasa',9,10,'DAKL',NULL),(344,17,84,27,'Rabu',1,3,'DAKL',NULL),(345,17,23,27,'Rabu',4,5,'BJAW',NULL),(346,17,57,27,'Rabu',6,7,'BIND',NULL),(347,17,16,27,'Rabu',8,10,'IPAS',NULL),(348,17,49,2,'Kamis',1,3,'PJOK',NULL),(349,17,84,27,'Kamis',4,6,'DAKL',NULL),(350,17,79,27,'Kamis',7,8,'BING',NULL),(351,17,29,27,'Kamis',9,10,'PPKN',NULL),(352,17,67,24,'Jumat',2,3,'INF',NULL),(353,17,80,27,'Jumat',4,5,'MTK',NULL),(354,17,57,27,'Jumat',6,7,'BIND',NULL),(355,17,27,24,'Jumat',8,9,'KKA',NULL),(356,17,82,23,'Jumat',10,13,'DAKL',NULL),(357,18,16,28,'Senin',2,4,'IPAS',NULL),(358,18,36,29,'Senin',5,8,'INF',NULL),(359,18,33,28,'Senin',9,10,'BJAW',NULL),(360,18,18,28,'Selasa',1,3,'PAIBP',NULL),(361,18,55,2,'Selasa',4,6,'PJOK',NULL),(362,18,85,28,'Selasa',7,8,'DULP',NULL),(363,18,17,28,'Selasa',9,10,'BING',NULL),(364,18,40,28,'Rabu',1,2,'BIND',NULL),(365,18,85,28,'Rabu',3,6,'DULP',NULL),(366,18,80,28,'Rabu',7,8,'MTK',NULL),(367,18,17,28,'Rabu',9,10,'BING',NULL),(368,18,40,28,'Kamis',1,2,'BIND',NULL),(369,18,85,28,'Kamis',3,6,'DULP',NULL),(370,18,13,28,'Kamis',7,8,'PPKN',NULL),(371,18,15,28,'Kamis',9,10,'SEJ',NULL),(372,18,58,28,'Jumat',2,2,'BK',NULL),(373,18,85,28,'Jumat',3,4,'DULP',NULL),(374,18,48,28,'Jumat',5,6,'SBUD',NULL),(375,18,73,29,'Jumat',7,8,'KKA',NULL),(376,18,16,28,'Jumat',9,11,'IPAS',NULL),(377,18,80,28,'Jumat',12,13,'MTK',NULL),(378,19,86,30,'Senin',2,4,'PAIBP',NULL),(379,19,6,30,'Senin',5,7,'IPAS',NULL),(380,19,87,30,'Senin',8,10,'DDKV',NULL),(381,19,69,30,'Selasa',1,1,'BK',NULL),(382,19,32,30,'Selasa',2,3,'BING',NULL),(383,19,88,30,'Selasa',4,6,'DDKV',NULL),(384,19,89,30,'Selasa',7,8,'BJAW',NULL),(385,19,90,30,'Selasa',9,10,'BIND',NULL),(386,19,62,30,'Rabu',1,2,'MTK',NULL),(387,19,2,2,'Rabu',3,5,'PJOK',NULL),(388,19,89,30,'Rabu',6,8,'DDKV',NULL),(389,19,91,31,'Rabu',9,10,'KKA',NULL),(390,19,24,31,'Kamis',1,2,'INF',NULL),(391,19,32,30,'Kamis',3,4,'BING',NULL),(392,19,5,30,'Kamis',5,6,'SBUD',NULL),(393,19,4,30,'Kamis',7,8,'SEJ',NULL),(394,19,13,30,'Kamis',9,10,'PPKN',NULL),(395,19,88,30,'Jumat',2,4,'DDKV',NULL),(396,19,62,30,'Jumat',5,6,'MTK',NULL),(397,19,6,30,'Jumat',7,9,'IPAS',NULL),(398,19,24,31,'Jumat',10,11,'INF',NULL),(399,19,90,30,'Jumat',12,13,'BIND',NULL),(400,20,91,31,'Senin',2,3,'KKA',NULL),(401,20,4,32,'Senin',4,5,'SEJ',NULL),(402,20,88,32,'Senin',6,8,'DDKV',NULL),(403,20,13,32,'Senin',9,10,'PPKN',NULL),(404,20,31,32,'Selasa',1,3,'IPAS',NULL),(405,20,90,32,'Selasa',4,5,'BIND',NULL),(406,20,32,32,'Selasa',6,7,'BING',NULL),(407,20,86,32,'Selasa',8,10,'PAIBP',NULL),(408,20,24,31,'Rabu',1,2,'INF',NULL),(409,20,14,32,'Rabu',3,3,'BK',NULL),(410,20,88,32,'Rabu',4,6,'DDKV',NULL),(411,20,92,32,'Rabu',7,8,'MTK',NULL),(412,20,90,32,'Rabu',9,10,'BIND',NULL),(413,20,32,32,'Kamis',1,2,'BING',NULL),(414,20,2,2,'Kamis',3,5,'PJOK',NULL),(415,20,24,31,'Kamis',6,7,'INF',NULL),(416,20,87,32,'Kamis',8,10,'DDKV',NULL),(417,20,5,32,'Jumat',2,3,'SBUD',NULL),(418,20,89,32,'Jumat',4,5,'BJAW',NULL),(419,20,89,32,'Jumat',6,8,'DDKV',NULL),(420,20,92,32,'Jumat',9,10,'MTK',NULL),(421,20,31,32,'Jumat',11,13,'IPAS',NULL),(422,21,55,2,'Senin',2,4,'PJOK',NULL),(423,21,29,33,'Senin',5,6,'PPKN',NULL),(424,21,93,4,'Senin',7,10,'DBP',NULL),(425,21,93,4,'Selasa',1,4,'DBP',NULL),(426,21,5,33,'Selasa',5,6,'SBUD',NULL),(427,21,50,33,'Selasa',7,8,'MTK',NULL),(428,21,19,33,'Selasa',9,10,'BIND',NULL),(429,21,86,33,'Rabu',1,3,'PAIBP',NULL),(430,21,16,33,'Rabu',4,6,'IPAS',NULL),(431,21,1,33,'Rabu',7,8,'BING',NULL),(432,21,19,33,'Rabu',9,10,'BIND',NULL),(433,21,28,4,'Kamis',1,2,'KKA',NULL),(434,21,28,4,'Kamis',3,6,'INF',NULL),(435,21,89,33,'Kamis',7,8,'BJAW',NULL),(436,21,50,33,'Kamis',9,10,'MTK',NULL),(437,21,4,33,'Jumat',2,3,'SEJ',NULL),(438,21,1,33,'Jumat',4,5,'BING',NULL),(439,21,16,33,'Jumat',6,8,'IPAS',NULL),(440,21,94,33,'Jumat',9,9,'BK',NULL),(441,21,93,4,'Jumat',10,13,'DBP',NULL),(442,22,28,4,'Senin',2,5,'INF',NULL),(443,22,1,34,'Senin',6,7,'BING',NULL),(444,22,16,34,'Senin',8,10,'IPAS',NULL),(445,22,16,34,'Selasa',1,3,'IPAS',NULL),(446,22,4,34,'Selasa',4,5,'SEJ',NULL),(447,22,94,34,'Selasa',6,6,'BK',NULL),(448,22,95,4,'Selasa',7,10,'DBP',NULL),(449,22,95,4,'Rabu',1,4,'DBP',NULL),(450,22,55,2,'Rabu',5,7,'PJOK',NULL),(451,22,86,34,'Rabu',8,10,'PAIBP',NULL),(452,22,30,34,'Kamis',1,2,'BIND',NULL),(453,22,33,34,'Kamis',3,4,'MTK',NULL),(454,22,29,34,'Kamis',5,6,'PPKN',NULL),(455,22,37,34,'Kamis',7,8,'BJAW',NULL),(456,22,1,34,'Kamis',9,10,'BING',NULL),(457,22,28,4,'Jumat',2,3,'KKA',NULL),(458,22,95,4,'Jumat',4,7,'DBP',NULL),(459,22,5,34,'Jumat',8,9,'SBUD',NULL),(460,22,33,34,'Jumat',10,11,'MTK',NULL),(461,22,30,34,'Jumat',12,13,'BIND',NULL),(462,23,4,35,'Senin',2,3,'SEJ',NULL),(463,23,5,35,'Senin',4,5,'SBUD',NULL),(464,23,71,35,'Senin',6,6,'BK',NULL),(465,23,57,35,'Senin',7,8,'BIND',NULL),(466,23,96,35,'Senin',9,10,'BING',NULL),(467,23,10,35,'Selasa',1,3,'PAIBP',NULL),(468,23,57,35,'Selasa',4,5,'BIND',NULL),(469,23,97,36,'Selasa',6,7,'INF',NULL),(470,23,98,36,'Selasa',8,10,'DAN',NULL),(471,23,99,36,'Rabu',1,3,'DAN',NULL),(472,23,100,35,'Rabu',4,5,'PPKN',NULL),(473,23,12,35,'Rabu',6,7,'MTK',NULL),(474,23,22,35,'Rabu',8,10,'IPAS',NULL),(475,23,96,35,'Kamis',1,2,'BING',NULL),(476,23,12,35,'Kamis',3,4,'MTK',NULL),(477,23,55,2,'Kamis',5,7,'PJOK',NULL),(478,23,99,36,'Kamis',8,10,'DAN',NULL),(479,23,22,35,'Jumat',2,4,'IPAS',NULL),(480,23,37,35,'Jumat',5,6,'BJAW',NULL),(481,23,98,36,'Jumat',7,9,'DAN',NULL),(482,23,91,36,'Jumat',10,11,'KKA',NULL),(483,23,97,36,'Jumat',12,13,'INF',NULL),(484,24,10,37,'Senin',2,4,'PAIBP',NULL),(485,24,91,36,'Senin',5,6,'KKA',NULL),(486,24,4,37,'Senin',7,8,'SEJ',NULL),(487,24,92,37,'Senin',9,10,'MTK',NULL),(488,24,55,2,'Selasa',1,3,'PJOK',NULL),(489,24,92,37,'Selasa',4,5,'MTK',NULL),(490,24,100,37,'Selasa',6,7,'PPKN',NULL),(491,24,96,37,'Selasa',8,9,'BING',NULL),(492,24,71,37,'Selasa',10,10,'BK',NULL),(493,24,22,37,'Rabu',1,3,'IPAS',NULL),(494,24,57,37,'Rabu',4,5,'BIND',NULL),(495,24,5,37,'Rabu',6,7,'SBUD',NULL),(496,24,98,36,'Rabu',8,10,'DAN',NULL),(497,24,57,37,'Kamis',1,2,'BIND',NULL),(498,24,96,37,'Kamis',3,4,'BING',NULL),(499,24,99,36,'Kamis',5,7,'DAN',NULL),(500,24,98,38,'Kamis',8,10,'DAN',NULL),(501,24,37,37,'Jumat',2,3,'BJAW',NULL),(502,24,98,36,'Jumat',4,5,'INF',NULL),(503,24,22,37,'Jumat',6,8,'IPAS',NULL),(504,24,99,38,'Jumat',9,11,'DAN',NULL),(505,24,98,38,'Jumat',12,13,'INF',NULL),(506,25,101,39,'Senin',2,3,'KTKI',NULL),(507,25,102,39,'Senin',4,6,'KTKI',NULL),(508,25,103,39,'Senin',7,10,'KTKI',NULL),(509,25,23,39,'Selasa',1,2,'BJPN',NULL),(510,25,96,39,'Selasa',3,4,'BING',NULL),(511,25,101,39,'Selasa',5,7,'KIK',NULL),(512,25,39,39,'Selasa',8,10,'MTK',NULL),(513,25,4,39,'Rabu',1,2,'BJAW',NULL),(514,25,7,39,'Rabu',3,4,'KTKI',NULL),(515,25,101,39,'Rabu',5,6,'KIK',NULL),(516,25,9,39,'Rabu',7,8,'MPTKI',NULL),(517,25,9,39,'Rabu',9,10,'PPKN',NULL),(518,25,3,39,'Kamis',1,3,'BIND',NULL),(519,25,101,39,'Kamis',4,5,'SEJ',NULL),(520,25,2,2,'Kamis',6,7,'PJOK',NULL),(521,25,18,39,'Kamis',8,10,'PAIBP',NULL),(522,25,102,39,'Jumat',2,4,'KTKI',NULL),(523,25,103,39,'Jumat',5,8,'KTKI',NULL),(524,25,96,39,'Jumat',9,10,'BING',NULL),(525,25,14,39,'Jumat',11,12,'BK',NULL),(526,26,2,2,'Senin',2,3,'PJOK',NULL),(527,26,39,40,'Senin',4,6,'MTK',NULL),(528,26,23,40,'Senin',7,8,'BJPN',NULL),(529,26,61,40,'Senin',9,10,'BING',NULL),(530,26,101,40,'Selasa',1,2,'KIK',NULL),(531,26,101,40,'Selasa',3,4,'KTKI',NULL),(532,26,7,40,'Selasa',5,6,'KTKI',NULL),(533,26,9,40,'Selasa',7,8,'MPTKI',NULL),(534,26,9,40,'Selasa',9,10,'PPKN',NULL),(535,26,3,40,'Rabu',1,3,'BIND',NULL),(536,26,102,40,'Rabu',4,6,'KTKI',NULL),(537,26,103,40,'Rabu',7,10,'KTKI',NULL),(538,26,18,40,'Kamis',1,3,'PAIBP',NULL),(539,26,103,40,'Kamis',4,7,'KTKI',NULL),(540,26,102,40,'Kamis',8,10,'KTKI',NULL),(541,26,101,40,'Jumat',2,3,'SEJ',NULL),(542,26,101,40,'Jumat',4,6,'KIK',NULL),(543,26,61,40,'Jumat',7,8,'BING',NULL),(544,26,14,40,'Jumat',9,10,'BK',NULL),(545,26,4,40,'Jumat',11,12,'BJAW',NULL),(546,27,18,7,'Senin',2,4,'PAIBP',NULL),(547,27,20,6,'Senin',5,6,'BK',NULL),(548,27,65,6,'Senin',7,10,'KRPL',NULL),(549,27,65,6,'Selasa',1,4,'KRPL',NULL),(550,27,73,6,'Selasa',5,7,'KRPL',NULL),(551,27,47,6,'Selasa',8,10,'KIK',NULL),(552,27,73,6,'Rabu',1,4,'KRPL',NULL),(553,27,34,7,'Rabu',5,6,'BING',NULL),(554,27,52,7,'Rabu',7,8,'SEJ',NULL),(555,27,33,7,'Rabu',9,10,'BJAW',NULL),(556,27,73,6,'Kamis',1,3,'KRPL',NULL),(557,27,104,6,'Kamis',4,5,'MPRPL',NULL),(558,27,21,2,'Kamis',6,7,'PJOK',NULL),(559,27,30,7,'Kamis',8,10,'BIND',NULL),(560,27,47,7,'Jumat',2,3,'KIK',NULL),(561,27,13,7,'Jumat',4,5,'PPKN',NULL),(562,27,105,7,'Jumat',6,7,'BJPN',NULL),(563,27,34,6,'Jumat',8,9,'BING',NULL),(564,27,11,7,'Jumat',10,12,'MTK',NULL),(565,28,47,9,'Senin',2,4,'KIK',NULL),(566,28,32,9,'Senin',5,6,'BING',NULL),(567,28,26,8,'Senin',7,10,'KRPL',NULL),(568,28,11,9,'Selasa',1,3,'MTK',NULL),(569,28,32,9,'Selasa',4,5,'BING',NULL),(570,28,21,2,'Selasa',6,7,'PJOK',NULL),(571,28,26,8,'Selasa',8,10,'KRPL',NULL),(572,28,65,8,'Rabu',1,4,'KRPL',NULL),(573,28,104,8,'Rabu',5,6,'MPRPL',NULL),(574,28,13,9,'Rabu',7,8,'PPKN',NULL),(575,28,52,9,'Rabu',9,10,'SEJ',NULL),(576,28,105,9,'Kamis',1,2,'BJPN',NULL),(577,28,20,9,'Kamis',3,4,'BK',NULL),(578,28,33,8,'Kamis',5,6,'BJAW',NULL),(579,28,65,8,'Kamis',7,10,'KRPL',NULL),(580,28,19,9,'Jumat',2,4,'BIND',NULL),(581,28,18,9,'Jumat',5,7,'PAIBP',NULL),(582,28,26,8,'Jumat',8,10,'KRPL',NULL),(583,28,47,8,'Jumat',11,12,'KIK',NULL),(584,29,105,41,'Senin',2,3,'BJPN',NULL),(585,29,40,41,'Senin',4,6,'BIND',NULL),(586,29,41,41,'Senin',7,10,'KTKJ',NULL),(587,29,34,41,'Selasa',1,2,'BING',NULL),(588,29,106,41,'Selasa',3,5,'KIK',NULL),(589,29,2,2,'Selasa',6,7,'PJOK',NULL),(590,29,36,41,'Selasa',8,10,'KTKJ',NULL),(591,29,101,41,'Rabu',1,2,'SEJ',NULL),(592,29,34,41,'Rabu',3,4,'BING',NULL),(593,29,35,41,'Rabu',5,6,'MPTKJ',NULL),(594,29,8,41,'Rabu',7,8,'KTKJ',NULL),(595,29,107,41,'Rabu',9,10,'BJAW',NULL),(596,29,86,41,'Kamis',1,3,'PAIBP',NULL),(597,29,38,41,'Kamis',4,5,'BK',NULL),(598,29,108,41,'Kamis',6,10,'KTKJ',NULL),(599,29,11,41,'Jumat',2,4,'MTK',NULL),(600,29,67,41,'Jumat',5,8,'KTKJ',NULL),(601,29,31,41,'Jumat',9,10,'PPKN',NULL),(602,29,106,41,'Jumat',11,12,'KIK',NULL),(603,30,107,42,'Senin',2,3,'BJAW',NULL),(604,30,8,43,'Senin',4,5,'KTKJ',NULL),(605,30,86,42,'Senin',6,8,'PAIBP',NULL),(606,30,34,42,'Senin',9,10,'BING',NULL),(607,30,24,42,'Selasa',1,3,'MTK',NULL),(608,30,52,42,'Selasa',4,5,'KIK',NULL),(609,30,108,43,'Selasa',6,10,'KTKJ',NULL),(610,30,2,2,'Rabu',1,2,'PJOK',NULL),(611,30,35,43,'Rabu',3,4,'MPTKJ',NULL),(612,30,38,42,'Rabu',5,6,'BK',NULL),(613,30,67,43,'Rabu',7,10,'KTKJ',NULL),(614,30,101,42,'Kamis',1,2,'SEJ',NULL),(615,30,34,42,'Kamis',3,4,'BING',NULL),(616,30,36,43,'Kamis',5,7,'KTKJ',NULL),(617,30,52,42,'Kamis',8,10,'KIK',NULL),(618,30,31,42,'Jumat',2,3,'PPKN',NULL),(619,30,40,42,'Jumat',4,6,'BIND',NULL),(620,30,41,43,'Jumat',7,10,'KTKJ',NULL),(621,30,105,42,'Jumat',11,12,'BJPN',NULL),(622,31,37,44,'Senin',2,3,'BJAW',NULL),(623,31,52,44,'Senin',4,6,'KIK',NULL),(624,31,109,44,'Senin',7,10,'MPBD',NULL),(625,31,29,44,'Selasa',1,2,'PPKN',NULL),(626,31,53,44,'Selasa',3,4,'BING',NULL),(627,31,110,44,'Selasa',5,7,'KBD',NULL),(628,31,42,44,'Selasa',8,10,'PAIBP',NULL),(629,31,52,44,'Rabu',1,2,'KIK',NULL),(630,31,12,44,'Rabu',3,5,'MTK',NULL),(631,31,49,2,'Rabu',6,7,'PJOK',NULL),(632,31,106,44,'Rabu',8,10,'KBD',NULL),(633,31,111,44,'Kamis',1,2,'BK',NULL),(634,31,112,44,'Kamis',3,4,'KBD',NULL),(635,31,113,44,'Kamis',5,8,'KBD',NULL),(636,31,106,44,'Kamis',9,10,'KBD',NULL),(637,31,110,44,'Jumat',2,3,'KBD',NULL),(638,31,107,44,'Jumat',4,5,'KBD',NULL),(639,31,114,44,'Jumat',6,7,'SEJ',NULL),(640,31,90,44,'Jumat',8,10,'BIND',NULL),(641,31,53,44,'Jumat',11,12,'BING',NULL),(642,32,113,45,'Senin',2,5,'KBD',NULL),(643,32,114,45,'Senin',6,7,'SEJ',NULL),(644,32,90,45,'Senin',8,10,'BIND',NULL),(645,32,12,45,'Selasa',1,3,'MTK',NULL),(646,32,109,45,'Selasa',4,7,'MPBD',NULL),(647,32,110,45,'Selasa',8,10,'KBD',NULL),(648,32,49,2,'Rabu',1,2,'PJOK',NULL),(649,32,106,45,'Rabu',3,4,'KBD',NULL),(650,32,52,45,'Rabu',5,6,'KIK',NULL),(651,32,112,45,'Rabu',7,8,'KBD',NULL),(652,32,53,45,'Rabu',9,10,'BING',NULL),(653,32,52,45,'Kamis',1,3,'KIK',NULL),(654,32,106,45,'Kamis',4,6,'KBD',NULL),(655,32,29,45,'Kamis',7,8,'PPKN',NULL),(656,32,37,45,'Kamis',9,10,'BJAW',NULL),(657,32,107,45,'Jumat',2,3,'KBD',NULL),(658,32,42,45,'Jumat',4,6,'PAIBP',NULL),(659,32,53,45,'Jumat',7,8,'BING',NULL),(660,32,46,45,'Jumat',9,10,'BK',NULL),(661,32,107,45,'Jumat',11,12,'KBD',NULL),(662,33,114,46,'Senin',2,3,'SEJ',NULL),(663,33,106,46,'Senin',4,7,'MPBD',NULL),(664,33,52,46,'Senin',8,10,'KIK',NULL),(665,33,46,46,'Selasa',1,2,'BK',NULL),(666,33,107,46,'Selasa',3,4,'KBD',NULL),(667,33,113,46,'Selasa',5,8,'KBD',NULL),(668,33,107,46,'Selasa',9,10,'BJAW',NULL),(669,33,106,46,'Rabu',1,2,'KBD',NULL),(670,33,52,46,'Rabu',3,4,'KIK',NULL),(671,33,110,46,'Rabu',5,7,'KBD',NULL),(672,33,30,46,'Rabu',8,10,'BIND',NULL),(673,33,53,46,'Kamis',1,2,'BING',NULL),(674,33,55,2,'Kamis',3,4,'PJOK',NULL),(675,33,92,46,'Kamis',5,7,'MTK',NULL),(676,33,110,46,'Kamis',8,10,'KBD',NULL),(677,33,54,46,'Jumat',2,4,'PAIBP',NULL),(678,33,53,46,'Jumat',5,6,'BING',NULL),(679,33,47,46,'Jumat',7,8,'KBD',NULL),(680,33,107,46,'Jumat',9,10,'KBD',NULL),(681,33,110,46,'Jumat',11,12,'PPKN',NULL),(682,34,115,17,'Senin',2,5,'KMP',NULL),(683,34,59,47,'Senin',6,8,'BIND',NULL),(684,34,64,47,'Senin',9,10,'MPMP',NULL),(685,34,115,17,'Selasa',1,2,'KMP',NULL),(686,34,115,17,'Selasa',3,5,'KMP',NULL),(687,34,54,47,'Selasa',6,8,'PAIBP',NULL),(688,34,61,47,'Selasa',9,10,'BING',NULL),(689,34,116,47,'Rabu',1,2,'KIK',NULL),(690,34,115,17,'Rabu',3,6,'KMP',NULL),(691,34,29,47,'Rabu',7,8,'PPKN',NULL),(692,34,105,47,'Rabu',9,10,'BJPN',NULL),(693,34,21,2,'Kamis',1,2,'PJOK',NULL),(694,34,114,47,'Kamis',3,4,'SEJ',NULL),(695,34,116,47,'Kamis',5,7,'KIK',NULL),(696,34,33,47,'Kamis',8,10,'MTK',NULL),(697,34,115,17,'Jumat',2,3,'KMP',NULL),(698,34,115,17,'Jumat',4,6,'KMP',NULL),(699,34,117,47,'Jumat',7,8,'BK',NULL),(700,34,61,47,'Jumat',9,10,'BING',NULL),(701,34,37,47,'Jumat',11,12,'BJAW',NULL),(702,35,116,48,'Senin',2,3,'KIK',NULL),(703,35,33,48,'Senin',4,6,'MTK',NULL),(704,35,118,17,'Senin',7,10,'KMP',NULL),(705,35,54,48,'Selasa',1,3,'PAIBP',NULL),(706,35,21,2,'Selasa',4,5,'PJOK',NULL),(707,35,118,17,'Selasa',6,8,'KMP',NULL),(708,35,64,48,'Selasa',9,10,'MPMP',NULL),(709,35,118,17,'Rabu',1,2,'KMP',NULL),(710,35,105,48,'Rabu',3,4,'BJPN',NULL),(711,35,29,48,'Rabu',5,6,'PPKN',NULL),(712,35,118,17,'Rabu',7,8,'KMP',NULL),(713,35,61,48,'Rabu',9,10,'BING',NULL),(714,35,116,48,'Kamis',1,3,'KIK',NULL),(715,35,37,48,'Kamis',4,5,'BJAW',NULL),(716,35,114,48,'Kamis',6,7,'SEJ',NULL),(717,35,118,17,'Kamis',8,10,'KMP',NULL),(718,35,59,48,'Jumat',2,4,'BIND',NULL),(719,35,61,48,'Jumat',5,6,'BING',NULL),(720,35,118,17,'Jumat',7,10,'KMP',NULL),(721,35,71,48,'Jumat',11,12,'BK',NULL),(722,36,62,49,'Senin',2,4,'MTK',NULL),(723,36,105,49,'Senin',5,6,'BJPN',NULL),(724,36,119,20,'Senin',7,10,'KMP',NULL),(725,36,116,49,'Selasa',1,3,'KIK',NULL),(726,36,74,49,'Selasa',4,6,'BIND',NULL),(727,36,119,20,'Selasa',7,8,'KMP',NULL),(728,36,119,20,'Selasa',9,10,'KMP',NULL),(729,36,21,2,'Rabu',1,2,'PJOK',NULL),(730,36,119,20,'Rabu',3,6,'KMP',NULL),(731,36,79,49,'Rabu',7,8,'BING',NULL),(732,36,29,49,'Rabu',9,10,'PPKN',NULL),(733,36,54,49,'Kamis',1,3,'PAIBP',NULL),(734,36,119,20,'Kamis',4,6,'KMP',NULL),(735,36,117,49,'Kamis',7,8,'BK',NULL),(736,36,64,49,'Kamis',9,10,'MPMP',NULL),(737,36,114,49,'Jumat',2,3,'SEJ',NULL),(738,36,23,49,'Jumat',4,5,'BJAW',NULL),(739,36,116,49,'Jumat',6,7,'KIK',NULL),(740,36,119,20,'Jumat',8,10,'KMP',NULL),(741,36,79,49,'Jumat',11,12,'BING',NULL),(742,37,100,20,'Senin',2,5,'KMP',NULL),(743,37,117,50,'Senin',6,7,'BK',NULL),(744,37,62,50,'Senin',8,10,'MTK',NULL),(745,37,100,20,'Selasa',1,4,'KMP',NULL),(746,37,114,50,'Selasa',5,6,'SEJ',NULL),(747,37,1,50,'Selasa',7,8,'BING',NULL),(748,37,23,50,'Selasa',9,10,'BJAW',NULL),(749,37,1,50,'Rabu',1,2,'BING',NULL),(750,37,116,50,'Rabu',3,5,'KIK',NULL),(751,37,21,2,'Rabu',6,7,'PJOK',NULL),(752,37,100,20,'Rabu',8,10,'KMP',NULL),(753,37,100,20,'Kamis',1,3,'KMP',NULL),(754,37,64,50,'Kamis',4,5,'MPMP',NULL),(755,37,105,50,'Kamis',6,7,'BJPN',NULL),(756,37,72,50,'Kamis',8,10,'PAIBP',NULL),(757,37,29,50,'Jumat',2,3,'PPKN',NULL),(758,37,116,50,'Jumat',4,5,'KIK',NULL),(759,37,100,20,'Jumat',6,7,'KMP',NULL),(760,37,74,50,'Jumat',8,10,'BIND',NULL),(761,37,100,20,'Jumat',11,12,'KMP',NULL),(762,38,120,51,'Senin',2,3,'KAK',NULL),(763,38,114,51,'Senin',4,5,'SEJ',NULL),(764,38,50,51,'Senin',6,8,'MTK',NULL),(765,38,37,51,'Senin',9,10,'BJAW',NULL),(766,38,49,2,'Selasa',1,2,'PJOK',NULL),(767,38,81,51,'Selasa',3,4,'BK',NULL),(768,38,78,24,'Selasa',5,10,'KAK',NULL),(769,38,96,51,'Rabu',1,2,'BING',NULL),(770,38,13,51,'Rabu',3,4,'PPKN',NULL),(771,38,105,51,'Rabu',5,6,'BJPN',NULL),(772,38,84,51,'Rabu',7,10,'KAK',NULL),(773,38,72,51,'Kamis',1,3,'PAIBP',NULL),(774,38,77,51,'Kamis',4,5,'KAK',NULL),(775,38,120,24,'Kamis',6,10,'KIK',NULL),(776,38,96,51,'Jumat',2,3,'BING',NULL),(777,38,121,24,'Jumat',4,7,'KAK',NULL),(778,38,19,51,'Jumat',8,10,'BIND',NULL),(779,38,76,51,'Jumat',11,12,'MPAK',NULL),(780,39,121,24,'Senin',2,5,'KAK',NULL),(781,39,72,52,'Senin',6,8,'PAIBP',NULL),(782,39,105,52,'Senin',9,10,'BJPN',NULL),(783,39,114,52,'Selasa',1,2,'SEJ',NULL),(784,39,49,2,'Selasa',3,4,'PJOK',NULL),(785,39,76,52,'Selasa',5,6,'MPAK',NULL),(786,39,76,52,'Selasa',7,10,'KAK',NULL),(787,39,43,24,'Rabu',1,6,'KAK',NULL),(788,39,96,52,'Rabu',7,8,'BING',NULL),(789,39,37,52,'Rabu',9,10,'BJAW',NULL),(790,39,120,24,'Kamis',1,5,'KIK',NULL),(791,39,19,52,'Kamis',6,8,'BIND',NULL),(792,39,83,52,'Kamis',9,10,'KAK',NULL),(793,39,13,52,'Jumat',2,3,'PPKN',NULL),(794,39,92,52,'Jumat',4,6,'MTK',NULL),(795,39,96,52,'Jumat',7,8,'BING',NULL),(796,39,120,52,'Jumat',9,10,'KAK',NULL),(797,39,94,52,'Jumat',11,12,'BK',NULL),(798,40,72,53,'Senin',2,4,'PAIBP',NULL),(799,40,49,2,'Senin',5,6,'PJOK',NULL),(800,40,75,53,'Senin',7,10,'KAK',NULL),(801,40,19,53,'Selasa',1,3,'BIND',NULL),(802,40,122,23,'Selasa',4,8,'KIK',NULL),(803,40,83,53,'Selasa',9,10,'KAK',NULL),(804,40,75,23,'Rabu',1,6,'KAK',NULL),(805,40,76,53,'Rabu',7,8,'MPAK',NULL),(806,40,23,53,'Rabu',9,10,'BJAW',NULL),(807,40,114,53,'Kamis',1,2,'SEJ',NULL),(808,40,105,53,'Kamis',3,4,'BJPN',NULL),(809,40,121,23,'Kamis',5,8,'KAK',NULL),(810,40,79,53,'Kamis',9,10,'BING',NULL),(811,40,12,53,'Jumat',2,4,'MTK',NULL),(812,40,78,53,'Jumat',5,6,'KAK',NULL),(813,40,13,53,'Jumat',7,8,'PPKN',NULL),(814,40,79,53,'Jumat',9,10,'BING',NULL),(815,40,111,53,'Jumat',11,12,'BK',NULL),(816,41,122,23,'Senin',2,6,'KIK',NULL),(817,41,105,54,'Senin',7,8,'BJPN',NULL),(818,41,79,54,'Senin',9,10,'BING',NULL),(819,41,76,54,'Selasa',1,2,'MPAK',NULL),(820,41,114,54,'Selasa',3,4,'SEJ',NULL),(821,41,111,54,'Selasa',5,6,'BK',NULL),(822,41,75,54,'Selasa',7,10,'KAK',NULL),(823,41,23,54,'Rabu',1,2,'BJAW',NULL),(824,41,79,54,'Rabu',3,4,'BING',NULL),(825,41,13,54,'Rabu',5,6,'PPKN',NULL),(826,41,122,54,'Rabu',7,8,'KAK',NULL),(827,41,78,54,'Rabu',9,10,'KAK',NULL),(828,41,82,23,'Kamis',1,4,'KAK',NULL),(829,41,57,54,'Kamis',5,7,'BIND',NULL),(830,41,39,54,'Kamis',8,10,'MTK',NULL),(831,41,49,2,'Jumat',2,3,'PJOK',NULL),(832,41,84,23,'Jumat',4,9,'KAK',NULL),(833,41,10,54,'Jumat',10,12,'PAIBP',NULL),(834,42,59,29,'Senin',2,4,'BIND',NULL),(835,42,85,55,'Senin',5,8,'KULW',NULL),(836,42,17,29,'Senin',9,10,'BING',NULL),(837,42,17,29,'Selasa',1,2,'BING',NULL),(838,42,85,55,'Selasa',3,6,'KULW',NULL),(839,42,123,55,'Selasa',7,8,'KIK',NULL),(840,42,52,29,'Selasa',9,10,'SEJ',NULL),(841,42,105,29,'Rabu',1,2,'BJPN',NULL),(842,42,123,29,'Rabu',3,5,'KIK',NULL),(843,42,123,29,'Rabu',6,8,'KULW',NULL),(844,42,92,29,'Rabu',9,10,'BJAW',NULL),(845,42,55,2,'Kamis',1,2,'PJOK',NULL),(846,42,80,29,'Kamis',3,5,'MTK',NULL),(847,42,123,29,'Kamis',6,8,'KULW',NULL),(848,42,105,29,'Kamis',9,10,'BJPN',NULL),(849,42,10,29,'Jumat',2,4,'PAIBP',NULL),(850,42,85,55,'Jumat',5,8,'KULW',NULL),(851,42,124,29,'Jumat',9,10,'BK',NULL),(852,42,13,29,'Jumat',11,12,'PPKN',NULL),(853,43,32,56,'Senin',2,3,'BING',NULL),(854,43,87,31,'Senin',4,6,'KDKV',NULL),(855,43,125,31,'Senin',7,10,'KDKV',NULL),(856,43,88,56,'Selasa',1,3,'KDKV',NULL),(857,43,89,56,'Selasa',4,6,'KDKV',NULL),(858,43,126,56,'Selasa',7,8,'KIK',NULL),(859,43,22,56,'Selasa',9,10,'SEJ',NULL),(860,43,81,56,'Rabu',1,2,'BK',NULL),(861,43,89,31,'Rabu',3,5,'KDKV',NULL),(862,43,87,31,'Rabu',6,7,'KDKV',NULL),(863,43,126,57,'Rabu',8,10,'KIK',NULL),(864,43,2,2,'Kamis',1,2,'PJOK',NULL),(865,43,89,56,'Kamis',3,4,'BJAW',NULL),(866,43,74,56,'Kamis',5,7,'BIND',NULL),(867,43,24,56,'Kamis',8,10,'MTK',NULL),(868,43,91,31,'Jumat',2,5,'MPDKV',NULL),(869,43,32,56,'Jumat',6,7,'BING',NULL),(870,43,42,56,'Jumat',8,10,'PAIBP',NULL),(871,43,29,56,'Jumat',11,12,'PPKN',NULL),(872,44,81,58,'Senin',2,3,'BK',NULL),(873,44,89,58,'Senin',4,6,'KDKV',NULL),(874,44,89,58,'Senin',7,8,'BJAW',NULL),(875,44,29,58,'Senin',9,10,'PPKN',NULL),(876,44,89,31,'Selasa',1,3,'KDKV',NULL),(877,44,125,31,'Selasa',4,7,'KDKV',NULL),(878,44,87,31,'Selasa',8,10,'KDKV',NULL),(879,44,87,58,'Rabu',1,2,'KDKV',NULL),(880,44,74,58,'Rabu',3,5,'BIND',NULL),(881,44,2,2,'Rabu',6,7,'PJOK',NULL),(882,44,24,58,'Rabu',8,10,'MTK',NULL),(883,44,126,58,'Kamis',1,3,'KIK',NULL),(884,44,22,58,'Kamis',4,5,'SEJ',NULL),(885,44,32,58,'Kamis',6,7,'BING',NULL),(886,44,42,58,'Kamis',8,10,'PAIBP',NULL),(887,44,126,58,'Jumat',2,3,'KIK',NULL),(888,44,32,58,'Jumat',4,5,'BING',NULL),(889,44,91,31,'Jumat',6,9,'MPDKV',NULL),(890,44,88,58,'Jumat',10,12,'KDKV',NULL),(891,45,30,59,'Senin',2,4,'BIND',NULL),(892,45,93,60,'Senin',5,6,'KPSPT',NULL),(893,45,127,61,'Senin',7,10,'KPSPT',NULL),(894,45,95,60,'Selasa',1,2,'KPSPT',NULL),(895,45,95,60,'Selasa',3,4,'KPSPT',NULL),(896,45,127,61,'Selasa',5,6,'KPSPT',NULL),(897,45,127,61,'Selasa',7,8,'KPSPT',NULL),(898,45,94,59,'Selasa',9,10,'BK',NULL),(899,45,97,60,'Rabu',1,4,'KPSPT',NULL),(900,45,32,59,'Rabu',5,6,'BING',NULL),(901,45,31,59,'Rabu',7,8,'PPKN',NULL),(902,45,128,59,'Rabu',9,10,'KIK',NULL),(903,45,97,60,'Kamis',1,4,'MPPSPT',NULL),(904,45,86,59,'Kamis',5,7,'PAIBP',NULL),(905,45,92,59,'Kamis',8,10,'MTK',NULL),(906,45,32,59,'Jumat',2,3,'BING',NULL),(907,45,114,59,'Jumat',4,5,'SEJ',NULL),(908,45,55,2,'Jumat',6,7,'PJOK',NULL),(909,45,52,59,'Jumat',8,9,'BJAW',NULL),(910,45,128,59,'Jumat',10,12,'KIK',NULL),(911,46,50,62,'Senin',2,4,'MTK',NULL),(912,46,128,62,'Senin',5,6,'KIK',NULL),(913,46,97,60,'Senin',7,10,'KPSPT',NULL),(914,46,86,62,'Selasa',1,3,'PAIBP',NULL),(915,46,128,62,'Selasa',4,6,'KIK',NULL),(916,46,114,62,'Selasa',7,8,'SEJ',NULL),(917,46,1,62,'Selasa',9,10,'BING',NULL),(918,46,127,61,'Rabu',1,4,'KPSPT',NULL),(919,46,97,60,'Rabu',5,8,'MPPSPT',NULL),(920,46,13,62,'Rabu',9,10,'PPKN',NULL),(921,46,127,61,'Kamis',1,2,'KPSPT',NULL),(922,46,127,61,'Kamis',3,4,'KPSPT',NULL),(923,46,95,60,'Kamis',5,6,'KPSPT',NULL),(924,46,95,60,'Kamis',7,8,'KPSPT',NULL),(925,46,93,60,'Kamis',9,10,'KPSPT',NULL),(926,46,55,2,'Jumat',2,3,'PJOK',NULL),(927,46,94,62,'Jumat',4,5,'BK',NULL),(928,46,30,62,'Jumat',6,8,'BIND',NULL),(929,46,1,62,'Jumat',9,10,'BING',NULL),(930,46,52,62,'Jumat',11,12,'BJAW',NULL),(931,47,45,63,'Senin',2,4,'BIND',NULL),(932,47,96,63,'Senin',5,6,'BING',NULL),(933,47,129,36,'Senin',7,8,'KAN',NULL),(934,47,91,36,'Senin',9,10,'MPAN',NULL),(935,47,130,38,'Selasa',1,3,'KAN',NULL),(936,47,98,38,'Selasa',4,7,'KAN',NULL),(937,47,99,38,'Selasa',8,10,'KAN',NULL),(938,47,91,38,'Rabu',1,3,'KAN',NULL),(939,47,129,38,'Rabu',4,6,'KIK',NULL),(940,47,4,63,'Rabu',7,8,'SEJ',NULL),(941,47,6,63,'Rabu',9,10,'PPKN',NULL),(942,47,130,38,'Kamis',1,3,'KAN',NULL),(943,47,129,38,'Kamis',4,5,'KIK',NULL),(944,47,10,37,'Kamis',6,8,'PAIBP',NULL),(945,47,96,37,'Kamis',9,10,'BING',NULL),(946,47,129,36,'Jumat',2,3,'MPAN',NULL),(947,47,55,2,'Jumat',4,5,'PJOK',NULL),(948,47,80,63,'Jumat',6,8,'MTK',NULL),(949,47,71,63,'Jumat',9,10,'BK',NULL),(950,47,92,63,'Jumat',11,12,'BJAW',NULL),(951,48,99,38,'Senin',2,4,'KAN',NULL),(952,48,98,38,'Senin',5,8,'KAN',NULL),(953,48,129,38,'Senin',9,10,'KAN',NULL),(954,48,129,36,'Selasa',1,3,'KIK',NULL),(955,48,91,36,'Selasa',4,5,'MPAN',NULL),(956,48,96,63,'Selasa',6,7,'BING',NULL),(957,48,45,63,'Selasa',8,10,'BIND',NULL),(958,48,107,63,'Rabu',1,2,'BJAW',NULL),(959,48,55,2,'Rabu',3,4,'PJOK',NULL),(960,48,91,36,'Rabu',5,7,'KAN',NULL),(961,48,130,38,'Rabu',8,10,'KAN',NULL),(962,48,71,63,'Kamis',1,2,'BK',NULL),(963,48,10,63,'Kamis',3,5,'PAIBP',NULL),(964,48,80,63,'Kamis',6,8,'MTK',NULL),(965,48,4,63,'Kamis',9,10,'SEJ',NULL),(966,48,130,38,'Jumat',2,4,'KAN',NULL),(967,48,129,38,'Jumat',5,6,'MPAN',NULL),(968,48,129,38,'Jumat',7,8,'KIK',NULL),(969,48,13,37,'Jumat',9,10,'PPKN',NULL),(970,48,96,37,'Jumat',11,12,'BING',NULL);
/*!40000 ALTER TABLE `jadwal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
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
-- Table structure for table `jurnal_detail_ketidakhadiran`
--

DROP TABLE IF EXISTS `jurnal_detail_ketidakhadiran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jurnal_detail_ketidakhadiran` (
  `id_detail` int NOT NULL AUTO_INCREMENT,
  `id_jurnal` int NOT NULL,
  `id_siswa` varchar(10) NOT NULL,
  `keterangan` enum('Sakit','Izin','Alpa') NOT NULL,
  `ref_izin_id` int DEFAULT NULL,
  `dicatat_oleh` int DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_detail`),
  UNIQUE KEY `id_jurnal` (`id_jurnal`,`id_siswa`),
  KEY `jurnal_detail_ketidakhadiran_ibfk_2` (`id_siswa`),
  KEY `jdk_ref_izin_id` (`ref_izin_id`),
  KEY `jdk_dicatat_oleh` (`dicatat_oleh`),
  CONSTRAINT `jdk_ibfk_izin` FOREIGN KEY (`ref_izin_id`) REFERENCES `pengajuan_izin_siswa` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `jdk_ibfk_user` FOREIGN KEY (`dicatat_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `jurnal_detail_ketidakhadiran_ibfk_1` FOREIGN KEY (`id_jurnal`) REFERENCES `jurnal_mengajar` (`id_jurnal`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `jurnal_detail_ketidakhadiran_ibfk_2` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`nis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jurnal_detail_ketidakhadiran`
--

LOCK TABLES `jurnal_detail_ketidakhadiran` WRITE;
/*!40000 ALTER TABLE `jurnal_detail_ketidakhadiran` DISABLE KEYS */;
/*!40000 ALTER TABLE `jurnal_detail_ketidakhadiran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jurnal_mengajar`
--

DROP TABLE IF EXISTS `jurnal_mengajar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jurnal_mengajar` (
  `id_jurnal` int NOT NULL AUTO_INCREMENT,
  `id_jadwal` int NOT NULL,
  `tanggal` date NOT NULL,
  `materi` text,
  `status_kehadiran_guru` enum('Hadir','Izin','Sakit','Tanpa Keterangan') NOT NULL DEFAULT 'Hadir',
  `catatan` text,
  `dicatat_pada` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_jurnal`),
  UNIQUE KEY `id_jadwal` (`id_jadwal`,`tanggal`),
  KEY `idx_jurnal_tanggal` (`tanggal`),
  CONSTRAINT `jurnal_mengajar_ibfk_1` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal` (`id_jadwal`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jurnal_mengajar`
--

LOCK TABLES `jurnal_mengajar` WRITE;
/*!40000 ALTER TABLE `jurnal_mengajar` DISABLE KEYS */;
INSERT INTO `jurnal_mengajar` VALUES (1,556,'2026-07-29','Pengenalan konsep Object-Oriented Programming (OOP): class, object, attribute, dan method beserta implementasi sederhana menggunakan Java.','Hadir','Pembelajaran berjalan dengan baik. Siswa aktif berdiskusi dan praktik membuat class sederhana.','2026-07-29 01:16:52',NULL),(2,557,'2026-07-29','Reading Comprehension: Understanding Descriptive and Procedure Text serta latihan vocabulary dan pronunciation.','Hadir','Sebagian besar siswa mampu memahami isi bacaan dan aktif menjawab pertanyaan.','2026-07-29 01:16:52',NULL),(3,558,'2026-07-29','Perkembangan Nasionalisme Indonesia pada Masa Pergerakan Nasional serta tokoh-tokoh penting.','Hadir','Diskusi kelas berlangsung aktif. Siswa mampu menghubungkan materi dengan kondisi bangsa saat ini.','2026-07-29 01:16:52',NULL),(4,559,'2026-07-29','Teks Pidhato Basa Jawa: struktur, unggah-ungguh basa, dan praktik menyusun pidato sederhana.','Hadir','Siswa mengikuti pembelajaran dengan baik dan mampu menyusun kerangka pidato sederhana.','2026-07-29 01:16:52',NULL);
/*!40000 ALTER TABLE `jurnal_mengajar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kelas`
--

DROP TABLE IF EXISTS `kelas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kelas` (
  `id_kelas` int NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(30) NOT NULL,
  `wali_kelas` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `jumlah_siswa` int NOT NULL DEFAULT '36',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_kelas`),
  UNIQUE KEY `nama_kelas` (`nama_kelas`),
  KEY `wali_kelas` (`wali_kelas`),
  CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`wali_kelas`) REFERENCES `guru` (`nip`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kelas`
--

LOCK TABLES `kelas` WRITE;
/*!40000 ALTER TABLE `kelas` DISABLE KEYS */;
INSERT INTO `kelas` VALUES (1,'X TKI 1','9900045',36,NULL),(2,'X TKI 2','9900078',36,NULL),(3,'X RPL 1','9900097',36,NULL),(4,'X RPL 2','9900096',36,NULL),(5,'X TKJ 1','9900010',36,NULL),(6,'X TKJ 2','9900011',36,NULL),(7,'X BD 1','9900048',36,NULL),(8,'X BD 2','9900023',36,NULL),(9,'X BD 3','9900033',36,NULL),(10,'X MP 1','9900040',36,NULL),(11,'X MP 2','9900071',36,NULL),(12,'X MP 3','9900074',36,NULL),(13,'X MP 4','9900061',36,NULL),(14,'X AK 1','9900082',36,NULL),(15,'X AK 2','9900011',36,NULL),(16,'X AK 3','9900015',36,NULL),(17,'X AK 4','9900091',36,NULL),(18,'X ULW','9900058',36,NULL),(19,'X DKV 1','9900091',36,NULL),(20,'X DKV 2','9900042',36,NULL),(21,'X PSPT 1','9900001',36,NULL),(22,'X PSPT 2','9900032',36,NULL),(23,'X AN 1','9900083',36,NULL),(24,'X AN 2','9900056',36,NULL),(25,'XI TKI 1','9900074',36,NULL),(26,'XI TKI 2','9900098',36,NULL),(27,'XI RPL 1','9900105',31,NULL),(28,'XI RPL 2','9900097',36,NULL),(29,'XI TKJ 1','9900028',36,NULL),(30,'XI TKJ 2','9900017',36,NULL),(31,'XI BD 1','9900034',36,NULL),(32,'XI BD 2','9900010',36,NULL),(33,'XI BD 3','9900059',36,NULL),(34,'XI MP 1','9900059',36,NULL),(35,'XI MP 2','9900013',36,NULL),(36,'XI MP 3','9900093',36,NULL),(37,'XI MP 4','9900037',36,NULL),(38,'XI AK 1','9900092',36,NULL),(39,'XI AK 2','9900057',36,NULL),(40,'XI AK 3','9900040',36,NULL),(41,'XI AK 4','9900069',36,NULL),(42,'XI ULW','9900089',36,NULL),(43,'XI DKV 1','9900009',36,NULL),(44,'XI DKV 2','9900073',36,NULL),(45,'XI PSPT 1','9900075',36,NULL),(46,'XI PSPT 2','9900005',36,NULL),(47,'XI AN 1','9900086',36,NULL),(48,'XI AN 2','9900075',36,NULL);
/*!40000 ALTER TABLE `kelas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ketidakhadiran_guru`
--

DROP TABLE IF EXISTS `ketidakhadiran_guru`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ketidakhadiran_guru` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_guru` int NOT NULL,
  `tanggal` date NOT NULL,
  `jenis` enum('Sakit','Izin','Tanpa Keterangan') NOT NULL,
  `keterangan` text,
  `foto_bukti` varchar(255) DEFAULT NULL,
  `status` enum('Menunggu','Disetujui','Ditolak') NOT NULL DEFAULT 'Menunggu',
  `disetujui_oleh` int DEFAULT NULL,
  `catatan` text,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ketidakhadiran_guru_id_guru` (`id_guru`),
  KEY `ketidakhadiran_guru_disetujui_oleh` (`disetujui_oleh`),
  CONSTRAINT `kethadiran_guru_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kethadiran_guru_ibfk_2` FOREIGN KEY (`disetujui_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ketidakhadiran_guru`
--

LOCK TABLES `ketidakhadiran_guru` WRITE;
/*!40000 ALTER TABLE `ketidakhadiran_guru` DISABLE KEYS */;
/*!40000 ALTER TABLE `ketidakhadiran_guru` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mapel`
--

DROP TABLE IF EXISTS `mapel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mapel` (
  `kode_mapel` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nama_mapel` varchar(150) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kode_mapel`),
  UNIQUE KEY `kode_mapel` (`kode_mapel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mapel`
--

LOCK TABLES `mapel` WRITE;
/*!40000 ALTER TABLE `mapel` DISABLE KEYS */;
INSERT INTO `mapel` VALUES ('BIND','Bahasa Indonesia',NULL),('BING','Bahasa Inggris',NULL),('BJAW','Bahasa Jawa',NULL),('BJPN','Bahasa Jepang',NULL),('BK','BK',NULL),('DAKL','Dasar AKL',NULL),('DAN','Dasar AN',NULL),('DBP','Dasar BP',NULL),('DDKV','Dasar DKV',NULL),('DMPLB','Dasar MPLB',NULL),('DPM','Dasar PM',NULL),('DPPLG','Dasar PPLG',NULL),('DTJKT','Dasar TJKT',NULL),('DTKI','Dasar TKI',NULL),('DULP','Dasar ULP',NULL),('INF','Informatika',NULL),('IPAS','IPAS',NULL),('KAK','Konsentrasi AK',NULL),('KAN','Konsentrasi AN',NULL),('KBD','Konsentrasi BD',NULL),('KDKV','Konsentrasi DKV',NULL),('KIK','Kreativitas, Inovasi, dan Kewirausahaan',NULL),('KKA','Koding dan Kecerdasan Artifisial',NULL),('KMP','Konsentrasi MP',NULL),('KPSPT','Konsentrasi PSPT',NULL),('KRPL','Konsentrasi RPL',NULL),('KTKI','Konsentrasi TKI',NULL),('KTKJ','Konsentrasi TKJ',NULL),('KULW','Konsentrasi ULW',NULL),('MPAK','Mapel Pilihan AK',NULL),('MPAN','Mapel Pilihan AN',NULL),('MPBD','Mapel Pilihan BD',NULL),('MPDKV','Mapel Pilihan DKV',NULL),('MPMP','Mapel Pilihan MP',NULL),('MPPSPT','Mapel Pilihan PSPT',NULL),('MPRPL','Mapel Pilihan RPL',NULL),('MPTKI','Mapel Pilihan TKI',NULL),('MPTKJ','Mapel Pilihan TKJ',NULL),('MTK','Matematika',NULL),('PAIBP','Pendidikan Agama Islam dan Budi Pekerti',NULL),('PJOK','PJOK',NULL),('PPKN','Pendidikan Pancasila',NULL),('SBUD','Seni Budaya',NULL),('SEJ','Sejarah',NULL);
/*!40000 ALTER TABLE `mapel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifikasi`
--

DROP TABLE IF EXISTS `notifikasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifikasi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `untuk_user_id` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `jenis` enum('dispen_siswa','izin_guru','izin_sakit_siswa','umum') NOT NULL DEFAULT 'umum',
  `ref_id` int DEFAULT NULL,
  `sudah_dibaca` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `notifikasi_untuk_user_id` (`untuk_user_id`),
  CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`untuk_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifikasi`
--

LOCK TABLES `notifikasi` WRITE;
/*!40000 ALTER TABLE `notifikasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifikasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `pengajuan_izin_siswa`
--

DROP TABLE IF EXISTS `pengajuan_izin_siswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengajuan_izin_siswa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nis` varchar(10) NOT NULL,
  `jenis_izin` enum('Sakit','Izin') NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text,
  `foto_bukti` varchar(255) DEFAULT NULL,
  `status` enum('Menunggu','Disetujui','Ditolak') NOT NULL DEFAULT 'Menunggu',
  `disetujui_oleh` int DEFAULT NULL,
  `catatan_wali` text,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pengajuan_izin_nis` (`nis`),
  KEY `pengajuan_izin_disetujui_oleh` (`disetujui_oleh`),
  CONSTRAINT `pengajuan_izin_ibfk_nis` FOREIGN KEY (`nis`) REFERENCES `siswa` (`nis`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pengajuan_izin_ibfk_wali` FOREIGN KEY (`disetujui_oleh`) REFERENCES `guru` (`id_guru`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengajuan_izin_siswa`
--

LOCK TABLES `pengajuan_izin_siswa` WRITE;
/*!40000 ALTER TABLE `pengajuan_izin_siswa` DISABLE KEYS */;
/*!40000 ALTER TABLE `pengajuan_izin_siswa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ruangan`
--

DROP TABLE IF EXISTS `ruangan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ruangan` (
  `id_ruangan` int NOT NULL AUTO_INCREMENT,
  `nama_ruangan` varchar(60) NOT NULL,
  `jenis_ruangan` enum('Kelas Biasa','Lab','Ruang Praktik','Lainnya') NOT NULL DEFAULT 'Kelas Biasa',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_ruangan`),
  UNIQUE KEY `nama_ruangan` (`nama_ruangan`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ruangan`
--

LOCK TABLES `ruangan` WRITE;
/*!40000 ALTER TABLE `ruangan` DISABLE KEYS */;
INSERT INTO `ruangan` VALUES (1,'Lab. KI 1','Lab',NULL),(2,'Lapangan','Ruang Praktik',NULL),(3,'Lab. TKJ 3','Lab',NULL),(4,'Lab. Broadcast','Lab',NULL),(5,'R 10','Kelas Biasa',NULL),(6,'Lab. RPL 1','Lab',NULL),(7,'R 57','Kelas Biasa',NULL),(8,'Lab. RPL 2','Lab',NULL),(9,'R 58','Kelas Biasa',NULL),(10,'R 32','Kelas Biasa',NULL),(11,'R 33','Kelas Biasa',NULL),(12,'R 23','Kelas Biasa',NULL),(13,'Lab. PM Belakang','Lab',NULL),(14,'R 24','Kelas Biasa',NULL),(15,'R 25','Kelas Biasa',NULL),(16,'R 11','Kelas Biasa',NULL),(17,'Lab. AP BAWAH','Lab',NULL),(18,'R 12','Kelas Biasa',NULL),(19,'R 13','Kelas Biasa',NULL),(20,'Lab. AP ATAS','Lab',NULL),(21,'R 14','Kelas Biasa',NULL),(22,'R 1','Kelas Biasa',NULL),(23,'Lab. AK ATAS','Lab',NULL),(24,'Lab. AK BAWAH','Lab',NULL),(25,'R 2','Kelas Biasa',NULL),(26,'R 3','Kelas Biasa',NULL),(27,'R 4','Kelas Biasa',NULL),(28,'R 22','Kelas Biasa',NULL),(29,'Lab. UPW 2 (Ticketing)','Lab',NULL),(30,'R 15','Kelas Biasa',NULL),(31,'Lab. DKV Komputer','Lab',NULL),(32,'R 16','Kelas Biasa',NULL),(33,'R 55','Kelas Biasa',NULL),(34,'R 56','Kelas Biasa',NULL),(35,'R 31','Kelas Biasa',NULL),(36,'Lab. AN COE 2','Lab',NULL),(37,'R 60','Kelas Biasa',NULL),(38,'Lab. AN COE 1','Lab',NULL),(39,'R 65','Kelas Biasa',NULL),(40,'R 66','Kelas Biasa',NULL),(41,'Lab. TKJ 1','Lab',NULL),(42,'R 34','Kelas Biasa',NULL),(43,'Lab. TKJ 2 (FO)','Lab',NULL),(44,'R 26','Kelas Biasa',NULL),(45,'R 61','Kelas Biasa',NULL),(46,'Lab. BD Depan','Lab',NULL),(47,'R 6','Kelas Biasa',NULL),(48,'R 7','Kelas Biasa',NULL),(49,'R 8','Kelas Biasa',NULL),(50,'R 9','Kelas Biasa',NULL),(51,'R 5','Kelas Biasa',NULL),(52,'R 62','Kelas Biasa',NULL),(53,'R 63','Kelas Biasa',NULL),(54,'R 64','Kelas Biasa',NULL),(55,'Lab. UPW 1 (Guiding)','Lab',NULL),(56,'R 17','Kelas Biasa',NULL),(57,'Lab. DKV Produksi','Lab',NULL),(58,'R 18','Kelas Biasa',NULL),(59,'R 40','Kelas Biasa',NULL),(60,'Lab. PSPT Editing','Lab',NULL),(61,'Lab. PSPT Studio','Lab',NULL),(62,'R 41','Kelas Biasa',NULL),(63,'R 59','Kelas Biasa',NULL);
/*!40000 ALTER TABLE `ruangan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `satpam`
--

DROP TABLE IF EXISTS `satpam`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `satpam` (
  `id_satpam` int NOT NULL AUTO_INCREMENT,
  `usn` varchar(20) NOT NULL,
  `nama_satpam` varchar(150) NOT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_satpam`),
  UNIQUE KEY `satpam_usn` (`usn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `satpam`
--

LOCK TABLES `satpam` WRITE;
/*!40000 ALTER TABLE `satpam` DISABLE KEYS */;
/*!40000 ALTER TABLE `satpam` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
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
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `siswa`
--

DROP TABLE IF EXISTS `siswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `siswa` (
  `nis` varchar(10) NOT NULL,
  `nisn` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nama_siswa` varchar(100) NOT NULL,
  `id_kelas` int NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`nis`),
  UNIQUE KEY `nis` (`nisn`),
  KEY `id_kelas` (`id_kelas`),
  CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `siswa`
--

LOCK TABLES `siswa` WRITE;
/*!40000 ALTER TABLE `siswa` DISABLE KEYS */;
INSERT INTO `siswa` VALUES ('000001','0094537732','ABID RIZKY NATANULLOH',27,NULL),('000002','0095072637','AHMAD SANIM SIBTU YAHYA',27,NULL),('000003','3096341242','AHMAD YAZRIL RIDHO FAJRIYA',27,NULL),('000004','0094998661','AISIVA PUJIANSARI',27,NULL),('000005','0099896424','AIZA HAYU PRAMUDYA',27,NULL),('000006','0109611324','ALBERT FACHREZY WIDODO',27,NULL),('000007','3090842341','ALMA LIATUL NURHALIZA',27,NULL),('000008','3092681045','ALVARO ALGOZHALI',27,NULL),('000009','0099166409','ARIEL WIJAYA SAPUTRA',27,NULL),('000010','0096026768','AS SYIFA ACINTYA TANITH A.P',27,NULL),('000011','0104537363','ASTITI FEBIANI SAMPURNA',27,NULL),('000012','0093467860','ASYIFA NUR DWI PURWANTI',27,NULL),('000013','0102439750','AWALISHA JUNY PURIPUTRI',27,NULL),('000014','0098068449','AZIZ ARIANSYAH',27,NULL),('000015','0093374852','DANESWARA PUWA HADI GAUTAMA',27,NULL),('000016','0095119290','DEDI PERMANA',27,NULL),('000017','0081359465','DIMAS SAIFUL',27,NULL),('000018','0093469584','DITA PUTRI CAHYANI',27,NULL),('000019','0097007164','ELGA BINTANG CAPUTRA',27,NULL),('000020','0092003573','FACHRIZA ADITYA ALRIFQI',27,NULL),('000021','0097600928','FANDY AHMAD RIYANTO',27,NULL),('000022','3092000334','FARA AZILA TRISNA PUTRI',27,NULL),('000023','0097530366','FELISA PUTRI MAHARANI',27,NULL),('000024','0096084978','ILHAM WICAKSONO',27,NULL),('000025','0082075177','IRFAN FANI SETIAWAN',27,NULL),('000026','0094537974','ISTIQOMAH',27,NULL),('000027','3098897489','KEYLLA PRISCYLIA PUTRI HARIANSYAH',27,NULL),('000028','3091930944','KHANZA HAMIDA KHUMAIROH',27,NULL),('000029','0099016906','KHAYARA MUKHBITA RAMADHINI SYAHPUTRA',27,NULL),('000030','3092068422','MARCHA SUKMA KINANTI',27,NULL),('000031','0106558253','MARDIANSYAH FANI PRATAMA',27,NULL);
/*!40000 ALTER TABLE `siswa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staf_tu`
--

DROP TABLE IF EXISTS `staf_tu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `staf_tu` (
  `id_staf` int NOT NULL AUTO_INCREMENT,
  `nip` varchar(18) NOT NULL,
  `nama_staf` varchar(150) NOT NULL,
  `jabatan` varchar(100) NOT NULL DEFAULT 'Staf TU',
  `no_hp` varchar(15) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_staf`),
  UNIQUE KEY `staf_nip` (`nip`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staf_tu`
--

LOCK TABLES `staf_tu` WRITE;
/*!40000 ALTER TABLE `staf_tu` DISABLE KEYS */;
INSERT INTO `staf_tu` VALUES (1,'0000001','Admin','Administrator',NULL,NULL,'2026-08-26 00:48:42','2026-08-26 00:48:42');
/*!40000 ALTER TABLE `staf_tu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('guru','guru_piket','staf_tu','satpam','wali_murid','kepala_sekolah','wakasis_siswa','wakasis_guru') NOT NULL,
  `id_guru` int DEFAULT NULL,
  `id_staf` int DEFAULT NULL,
  `id_satpam` int DEFAULT NULL,
  `nisn_siswa` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username` (`username`),
  KEY `users_id_guru` (`id_guru`),
  KEY `users_id_staf` (`id_staf`),
  KEY `users_id_satpam` (`id_satpam`),
  KEY `users_nisn_siswa` (`nisn_siswa`),
  CONSTRAINT `users_ibfk_guru` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id_guru`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `users_ibfk_satpam` FOREIGN KEY (`id_satpam`) REFERENCES `satpam` (`id_satpam`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `users_ibfk_siswa` FOREIGN KEY (`nisn_siswa`) REFERENCES `siswa` (`nisn`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `users_ibfk_staf` FOREIGN KEY (`id_staf`) REFERENCES `staf_tu` (`id_staf`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'0000001','$2y$10$iq7LoKIF75qpLoxcXM8.AuII4eUO/ybi1Ywtciu5/n4ATk8dmEEd.','staf_tu',NULL,1,NULL,NULL,1,NULL,'2026-08-26 00:49:28','2026-08-26 00:49:28');
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

-- Dump completed on 2026-08-26  7:49:42

