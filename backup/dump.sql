CREATE TABLE IF NOT EXISTS `prefix_backup_files` (
  `filepath` varchar(32768),
  `status` varchar(),
  PRIMARY KEY (`filepath`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `prefix_backup_options` (
  `option` varchar(50),
  `value` varchar(32768)
  PRIMARY KEY (`option`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `prefix_backup_options` VALUES ('skip_pathre_count','4');
INSERT INTO `prefix_backup_options` VALUES ('skip_pathre_0','\/compiled.*?');
INSERT INTO `prefix_backup_options` VALUES ('skip_pathre_1','\/cache.*?');
INSERT INTO `prefix_backup_options` VALUES ('skip_pathre_2','\/tmp.*?');
INSERT INTO `prefix_backup_options` VALUES ('skip_pathre_3','\/log.*?');
INSERT INTO `prefix_backup_options` VALUES ('backup_filename','backup.zip');
INSERT INTO `prefix_backup_options` VALUES ('backup_path','./');
