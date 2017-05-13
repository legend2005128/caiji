/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : 127.0.0.1:3306
Source Database       : qiye_collection

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-09-12 15:22:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `yuncai_company_evaluate`
-- ----------------------------
DROP TABLE IF EXISTS `yuncai_company_evaluate`;
CREATE TABLE `yuncai_company_evaluate` (
  `id` varchar(32) NOT NULL COMMENT '主键id',
  `company_name` varchar(200) NOT NULL COMMENT '公司名称',
  `page_path` varchar(200) NOT NULL COMMENT '页面存放路径',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `province` varchar(200) DEFAULT NULL COMMENT '省份',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yuncai_company_evaluate
-- ----------------------------
INSERT INTO `yuncai_company_evaluate` VALUES ('4ci3w6toqcm6omvgvps9s967ftf45bav', '江苏筑牛网络科技有限公司苏州运营中心', 'http://127.0.0.1:83/caiji/html/jiangsu/9f191e3450b1c3aff0249d1ac8b234b7.html', '2016-09-12 15:18:02', '江苏省');
INSERT INTO `yuncai_company_evaluate` VALUES ('c7dg7j83qfojuj7mkphwjv2wekct7e55', '江苏筑牛网络科技有限公司', 'http://127.0.0.1:83/caiji/html/jiangsu/49875773beff87ef949235655d012b64.html', '2016-09-12 15:18:15', '江苏省');
