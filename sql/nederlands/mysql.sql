CREATE TABLE adslight_listing (
  lid         INT(15)          NOT NULL AUTO_INCREMENT,
  cid         INT(15)          NOT NULL DEFAULT '0',
  title       VARCHAR(100)     NOT NULL DEFAULT '',
  status      INT(3)           NOT NULL DEFAULT '0',
  expire      CHAR(3)          NOT NULL DEFAULT '',
  type        VARCHAR(15)      NOT NULL DEFAULT '',
  desctext    TEXT             NOT NULL,
  tel         VARCHAR(15)      NOT NULL DEFAULT '',
  price       DECIMAL(20, 2)   NOT NULL DEFAULT '0.00',
  typeprice   VARCHAR(15)      NOT NULL DEFAULT '',
  typeusure   VARCHAR(15)      NOT NULL DEFAULT '',
  date        INT(10)          NOT NULL DEFAULT '0',
  email       VARCHAR(100)     NOT NULL DEFAULT '',
  submitter   VARCHAR(60)      NOT NULL DEFAULT '',
  usid        VARCHAR(6)       NOT NULL DEFAULT '',
  town        VARCHAR(200)     NOT NULL DEFAULT '',
  country     VARCHAR(200)     NOT NULL DEFAULT '',
  contactby   VARCHAR(50)      NOT NULL DEFAULT '',
  premium     CHAR(3)          NOT NULL DEFAULT '',
  valid       VARCHAR(11)      NOT NULL DEFAULT '',
  photo       VARCHAR(100)     NOT NULL DEFAULT '0',
  hits        INT(11)          NOT NULL DEFAULT '0',
  item_rating DOUBLE(6, 4)     NOT NULL DEFAULT '0.0000',
  item_votes  INT(11) UNSIGNED NOT NULL DEFAULT '0',
  user_rating DOUBLE(6, 4)     NOT NULL DEFAULT '0.0000',
  user_votes  INT(11) UNSIGNED NOT NULL DEFAULT '0',
  comments    INT(11) UNSIGNED NOT NULL DEFAULT '0',
  remind      INT(11)          NOT NULL DEFAULT '0',
  PRIMARY KEY (lid)
)
  ENGINE = MyISAM;

CREATE TABLE adslight_categories (
  cid             INT(11)         NOT NULL AUTO_INCREMENT,
  pid             INT(5) UNSIGNED NOT NULL DEFAULT '0',
  title           VARCHAR(50)     NOT NULL DEFAULT '',
  cat_desc        VARCHAR(200)    NOT NULL DEFAULT '',
  cat_keywords    VARCHAR(1000)   NOT NULL DEFAULT '',
  img             VARCHAR(150)    NOT NULL DEFAULT 'default.png',
  ordre           INT(5)          NOT NULL DEFAULT '0',
  affprice        INT(5)          NOT NULL DEFAULT '1',
  cat_moderate    INT(5)          NOT NULL DEFAULT '1',
  moderate_subcat INT(5)          NOT NULL DEFAULT '1',
  PRIMARY KEY (cid)
)
  ENGINE = MyISAM;

INSERT INTO `adslight_categories` (`cid`, `pid`, `title`, `cat_desc`, `cat_keywords`, `img`, `ordre`, `affprice`, `cat_moderate`, `moderate_subcat`) VALUES
  (1, 0, 'Auto / Moto', '', '', 'car.png', 0, 1, 1, 1),
  (2, 0, 'Echt', '', '', 'home.png', 0, 1, 1, 1),
  (3, 0, 'Good Business', '', '', 'jewelry.png', 0, 1, 1, 1),
  (4, 1, 'Auto', '', '', 'default.png', 0, 1, 1, 1),
  (5, 1, 'Auto Utilities', '', '', 'default.png', 0, 1, 1, 1),
  (6, 1, 'Motos / Scooters', '', '', 'default.png', 0, 1, 1, 1),
  (7, 1, 'Caravans', '', '', 'default.png', 0, 1, 1, 1),
  (8, 1, 'Accessoires', '', '', 'default.png', 0, 1, 1, 1),
  (9, 2, 'Verkoop huizen', '', '', 'default.png', 0, 1, 1, 1),
  (10, 2, 'Verkoop Appartementen', '', '', 'default.png', 0, 1, 1, 1),
  (11, 2, 'Verhuur huizen', '', '', 'default.png', 0, 1, 1, 1),
  (12, 2, 'Woningen te huur', '', '', 'default.png', 0, 1, 1, 1),
  (13, 2, 'Appartementen', '', '', 'default.png', 0, 1, 1, 1),
  (14, 3, 'Amusement', '', '', 'default.png', 0, 1, 1, 1),
  (15, 3, 'Bricolage', '', '', 'default.png', 0, 1, 1, 1),
  (16, 0, 'Computer', '', '', 'computer.png', 0, 1, 1, 1),
  (17, 0, 'Telefonie', '', '', 'telephony.png', 0, 1, 1, 1),
  (18, 0, 'Sport en Fiets', '', '', 'mountain_bike.png', 0, 1, 1, 1),
  (19, 0, 'Muziek', '', '', 'guitar.png', 0, 1, 1, 1),
  (20, 19, 'Cd Muziek', '', '', 'default.png', 0, 1, 1, 1),
  (21, 19, 'Dvd Muziek', '', '', 'default.png', 0, 1, 1, 1),
  (22, 19, 'Muziekinstrumenten', '', '', 'default.png', 0, 1, 1, 1),
  (23, 16, 'Onderdelen', '', '', 'default.png', 0, 1, 1, 1),
  (24, 16, 'Computers', '', '', 'default.png', 0, 1, 1, 1),
  (25, 16, 'Games', '', '', 'default.png', 0, 1, 1, 1),
  (26, 16, 'Software', '', '', 'default.png', 0, 1, 1, 1),
  (27, 3, 'Kwekerij accessoires', '', '', 'default.png', 0, 1, 1, 1),
  (28, 0, 'Toestellen', '', '', 'appliances.png', 0, 1, 1, 1),
  (29, 28, 'TV', '', '', 'default.png', 0, 1, 1, 1),
  (30, 28, 'Draagbare video', '', '', 'default.png', 0, 1, 1, 1),
  (31, 28, 'Onderlegschijven', '', '', 'default.png', 0, 1, 1, 1),
  (32, 28, 'Ovens', '', '', 'default.png', 0, 1, 1, 1),
  (33, 18, 'mountainbike', '', '', 'default.png', 0, 1, 1, 1),
  (34, 18, 'Paardrijden', '', '', 'default.png', 0, 1, 1, 1),
  (35, 18, 'Kleding', '', '', 'default.png', 0, 1, 1, 1),
  (36, 18, 'Sport Schoenen', '', '', 'default.png', 0, 1, 1, 1),
  (37, 18, 'Duiken - Accessoires', '', '', 'default.png', 0, 1, 1, 1);

CREATE TABLE adslight_type (
  id_type  INT(11)      NOT NULL AUTO_INCREMENT,
  nom_type VARCHAR(150) NOT NULL DEFAULT '',
  PRIMARY KEY (id_type)
)
  ENGINE = MyISAM;


INSERT INTO adslight_type VALUES (1, 'Verkoop:');
INSERT INTO adslight_type VALUES (2, 'Zoeken:');
INSERT INTO adslight_type VALUES (3, 'Geven:');
INSERT INTO adslight_type VALUES (4, 'Uitwisseling:');
INSERT INTO adslight_type VALUES (5, 'Huur:');

CREATE TABLE adslight_price (
  id_price  INT(11)      NOT NULL AUTO_INCREMENT,
  nom_price VARCHAR(150) NOT NULL DEFAULT '',
  PRIMARY KEY (id_price)
)
  ENGINE = MyISAM;


INSERT INTO adslight_price VALUES (1, 'Farm prijzen');
INSERT INTO adslight_price VALUES (2, 'Maximale prijs');
INSERT INTO adslight_price VALUES (3, 'Verhandelbaar');

CREATE TABLE adslight_usure (
  id_usure  INT(11)      NOT NULL AUTO_INCREMENT,
  nom_usure VARCHAR(150) NOT NULL DEFAULT '',
  PRIMARY KEY (id_usure)
)
  ENGINE = MyISAM;


INSERT INTO adslight_usure VALUES (1, 'Negen');
INSERT INTO adslight_usure VALUES (2, 'Gebruikt');
INSERT INTO adslight_usure VALUES (3, 'Verwend');
INSERT INTO adslight_usure VALUES (4, 'Versleten');

CREATE TABLE adslight_ip_log (
  ip_id     INT(11)      NOT NULL AUTO_INCREMENT,
  lid       INT(11)      NOT NULL DEFAULT '0',
  date      VARCHAR(25)           DEFAULT NULL,
  submitter VARCHAR(60)  NOT NULL DEFAULT '',
  ipnumber  VARCHAR(150) NOT NULL DEFAULT '',
  email     VARCHAR(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`ip_id`)
)
  ENGINE = MyISAM
  AUTO_INCREMENT = 1;

#
# Table structure for table `adslight_votedata`
#

CREATE TABLE adslight_item_votedata (
  ratingid        INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
  lid             INT(11) UNSIGNED    NOT NULL DEFAULT '0',
  ratinguser      INT(11) UNSIGNED    NOT NULL DEFAULT '0',
  rating          TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  ratinghostname  VARCHAR(60)         NOT NULL DEFAULT '',
  ratingtimestamp INT(10)             NOT NULL DEFAULT '0',
  PRIMARY KEY (ratingid),
  KEY ratinguser (ratinguser),
  KEY ratinghostname (ratinghostname)
)
  ENGINE = MyISAM;

#
# Table structure for table `adslight_votedata`
#

CREATE TABLE adslight_user_votedata (
  ratingid        INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
  usid            INT(11) UNSIGNED    NOT NULL DEFAULT '0',
  ratinguser      INT(11) UNSIGNED    NOT NULL DEFAULT '0',
  rating          TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  ratinghostname  VARCHAR(60)         NOT NULL DEFAULT '',
  ratingtimestamp INT(10)             NOT NULL DEFAULT '0',
  PRIMARY KEY (ratingid),
  KEY ratinguser (ratinguser),
  KEY ratinghostname (ratinghostname)
)
  ENGINE = MyISAM;

CREATE TABLE adslight_pictures (
  cod_img       INT(11)      NOT NULL AUTO_INCREMENT,
  title         VARCHAR(255) NOT NULL,
  date_added    INT(10)      NOT NULL DEFAULT '0',
  date_modified INT(10)      NOT NULL DEFAULT '0',
  lid           INT(11)      NOT NULL DEFAULT '0',
  uid_owner     VARCHAR(50)  NOT NULL,
  url           TEXT         NOT NULL,
  PRIMARY KEY (cod_img)
)
  ENGINE = MyISAM;

CREATE TABLE adslight_replies (
  r_lid     INT(11)      NOT NULL AUTO_INCREMENT,
  lid       INT(11)      NOT NULL DEFAULT '0',
  title     VARCHAR(50)  NOT NULL DEFAULT '',
  date      INT(10)      NOT NULL DEFAULT '0',
  submitter VARCHAR(60)  NOT NULL DEFAULT '',
  message   TEXT         NOT NULL,
  tele      VARCHAR(15)  NOT NULL DEFAULT '',
  email     VARCHAR(100) NOT NULL DEFAULT '',
  r_usid    INT(11)      NOT NULL DEFAULT '0',
  PRIMARY KEY (r_lid)
)
  ENGINE = MyISAM;
