CREATE DATABASE IF NOT EXISTS job_test;

CREATE TABLE `job_test`.user (
  id smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  role_id tinyint(3) UNSIGNED NOT NULL COMMENT 'Логин в системе',
  login varchar(50) NOT NULL COMMENT 'Логин в системе',
  password varchar(32) NOT NULL COMMENT 'Пароль в системе',
  PRIMARY KEY (id),
  INDEX Login (login),
  INDEX role_id (role_id)
)
ENGINE = INNODB
AUTO_INCREMENT = 10
CHARACTER SET utf8
COLLATE utf8_general_ci
COMMENT = '{"alias":"us"}';

INSERT INTO `job_test`.user(
  id, role_id, login, password)
  VALUES (1, 1, 'admin', 'admin12345');

INSERT INTO `job_test`.user(
  id, role_id, login, password)
  VALUES (2, 2, 'guest', '');

CREATE TABLE `job_test`.news (
  id smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id smallint(5) UNSIGNED NOT NULL COMMENT 'Идентификатор автора',
  title varchar(50) NOT NULL COMMENT 'Название',
  annotate text NOT NULL COMMENT 'Краткое описание',
  body text NOT NULL COMMENT 'Содержание',
  PRIMARY KEY (id),
  INDEX user_id (user_id),
  INDEX title (title),
  CONSTRAINT news_fk1 FOREIGN KEY (user_id)
  REFERENCES `job_test`.user (id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci
COMMENT = '{"alias":"nws"}';