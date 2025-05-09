/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : mvp_php

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2025-05-02 11:53:37
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `mahasiswa`
-- ----------------------------
DROP TABLE IF EXISTS `mahasiswa`;
CREATE TABLE `mahasiswa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nim` varchar(100) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tempat` varchar(100) NOT NULL,
  `tl` varchar(100) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telp` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- ----------------------------
-- Records of mahasiswa
-- ----------------------------
INSERT INTO `mahasiswa` VALUES ('1', '2202123', 'Abdullah', 'Garut', '2020-12-11', 'Laki-laki', 'abdul@upi.edu', '088970803025');
INSERT INTO `mahasiswa` VALUES ('2', '2202124', 'Wahyu', 'Cimahi', '2020-12-14', 'Laki-laki', 'wah@upi.edu', '089678898545');
INSERT INTO `mahasiswa` VALUES ('4', '2202125', 'Ayang', 'Bandung', '2020-11-29', 'Perempuan', 'ay@gmail.com', '081321778980');
INSERT INTO `mahasiswa` VALUES ('5', '2202126', 'Rakha', 'Palembang', '2021-01-04', 'Laki-laki', 'palembang@gmail.com', '088970803025');
INSERT INTO `mahasiswa` VALUES ('6', '2202127', 'Prilla', 'Seoul', '2001-05-05', 'Perempuan', 'prillarosaria@upi.edu', '081234876235');
INSERT INTO `mahasiswa` VALUES ('7', '2202128', 'Son', 'Canada', '1994-02-21', 'Perempuan', 'chrstjsmn@gmail.com', '081573038425');
INSERT INTO `mahasiswa` VALUES ('8', '2202129', 'Jeno', 'Incheon', '2000-12-23', 'Laki-laki', 'jeno@gmail.com', '08138746239');
INSERT INTO `mahasiswa` VALUES ('9', '2202130', 'Mark', 'Canada', '1999-08-20', 'Laki-laki', 'mark@upi.edu', '08237218473');
