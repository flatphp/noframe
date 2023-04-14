CREATE DATABASE example DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 创建用户基本信息表
CREATE TABLE `user_info` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `name` varchar(60) NOT NULL DEFAULT '' COMMENT '用户名',
  `age` tinyint NOT NULL DEFAULT '0' COMMENT '年纪',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户表';
