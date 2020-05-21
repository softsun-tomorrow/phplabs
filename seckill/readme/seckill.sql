/*
SQLyog Ultimate v8.32 
MySQL - 5.1.54-community : Database - seckill
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`seckill` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `seckill`;

/*Table structure for table `sk_books` */

DROP TABLE IF EXISTS `sk_books`;

CREATE TABLE `sk_books` (
  `bookid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '预约ID',
  `skid` mediumint(8) unsigned NOT NULL COMMENT '秒杀ID',
  `userid` mediumint(8) unsigned NOT NULL COMMENT '用户ID',
  `booktime` int(10) unsigned NOT NULL COMMENT '预约时间',
  PRIMARY KEY (`bookid`),
  UNIQUE KEY `skbook` (`skid`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `sk_books` */

/*Table structure for table `sk_orders` */

DROP TABLE IF EXISTS `sk_orders`;

CREATE TABLE `sk_orders` (
  `orderid` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `shop_order_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '商城订单ID',
  `skid` mediumint(8) unsigned NOT NULL COMMENT '秒杀ID',
  `userid` mediumint(8) unsigned NOT NULL COMMENT '用户ID',
  `create_time` int(10) unsigned NOT NULL COMMENT '秒杀时间',
  `finish_time` int(10) unsigned NOT NULL COMMENT '完成时间',
  `status` tinyint(1) unsigned NOT NULL COMMENT '订单状态',
  PRIMARY KEY (`orderid`),
  UNIQUE KEY `unique_shop_order_id` (`shop_order_id`,`orderid`,`skid`,`userid`),
  KEY `skorder` (`skid`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `sk_orders` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
