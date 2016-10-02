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
  (1, 0, 'Wagen / Motorrad', '', '', 'car.png', 0, 1, 1, 1),
  (2, 0, 'Immobilienhandel', '', '', 'home.png', 0, 1, 1, 1),
  (3, 0, 'Ein gutes Geschäft', '', '', 'jewelry.png', 0, 1, 1, 1),
  (4, 1, 'Gebrauchtwagen', '', '', 'default.png', 0, 1, 1, 1),
  (5, 1, 'Vans', '', '', 'default.png', 0, 1, 1, 1),
  (6, 1, 'Motorrad / Motorroller', '', '', 'default.png', 0, 1, 1, 1),
  (7, 1, 'Caravaning', '', '', 'default.png', 0, 1, 1, 1),
  (8, 1, 'Zubehör', '', '', 'default.png', 0, 1, 1, 1),
  (9, 2, 'Verkauf Immobilien', '', '', 'default.png', 0, 1, 1, 1),
  (10, 2, 'Verkauf Appartements', '', '', 'default.png', 0, 1, 1, 1),
  (11, 2, 'Vermietung Wohnungen', '', '', 'default.png', 0, 1, 1, 1),
  (12, 2, 'Ferienwohnungen', '', '', 'default.png', 0, 1, 1, 1),
  (13, 2, 'Ferienhäuser', '', '', 'default.png', 0, 1, 1, 1),
  (14, 3, 'Freizeitbeschäftigungen', '', '', 'default.png', 0, 1, 1, 1),
  (15, 3, 'Heimwerkerarbeit', '', '', 'default.png', 0, 1, 1, 1),
  (16, 0, 'Informatik', '', '', 'computer.png', 0, 1, 1, 1),
  (17, 0, 'Telefonie', '', '', 'telephony.png', 0, 1, 1, 1),
  (18, 0, 'Sport und Fahrrad', '', '', 'mountain_bike.png', 0, 1, 1, 1),
  (19, 0, 'Musik', '', '', 'guitar.png', 0, 1, 1, 1),
  (20, 19, 'CD Musik', '', '', 'default.png', 0, 1, 1, 1),
  (21, 19, 'Dvd Musik', '', '', 'default.png', 0, 1, 1, 1),
  (22, 19, 'Musikinstrumente', '', '', 'default.png', 0, 1, 1, 1),
  (23, 16, 'Einzelteil', '', '', 'default.png', 0, 1, 1, 1),
  (24, 16, 'Computer', '', '', 'default.png', 0, 1, 1, 1),
  (25, 16, 'Spiel', '', '', 'default.png', 0, 1, 1, 1),
  (26, 16, 'Software ', '', '', 'default.png', 0, 1, 1, 1),
  (27, 3, 'Babymöbel', '', '', 'default.png', 0, 1, 1, 1),
  (28, 0, 'Haushaltsgeräte', '', '', 'appliances.png', 0, 1, 1, 1),
  (29, 28, 'Fernseher', '', '', 'default.png', 0, 1, 1, 1),
  (30, 28, 'Video-Player', '', '', 'default.png', 0, 1, 1, 1),
  (31, 28, 'Waschmaschinen', '', '', 'default.png', 0, 1, 1, 1),
  (32, 28, 'Backöfen', '', '', 'default.png', 0, 1, 1, 1),
  (33, 18, 'Mtb', '', '', 'default.png', 0, 1, 1, 1),
  (34, 18, 'Reitkunst', '', '', 'default.png', 0, 1, 1, 1),
  (35, 18, 'Kleidungsstück', '', '', 'default.png', 0, 1, 1, 1),
  (36, 18, 'Turnschuhe', '', '', 'default.png', 0, 1, 1, 1),
  (37, 18, ' Tauch-Zubehör', '', '', 'default.png', 0, 1, 1, 1);

CREATE TABLE adslight_type (
  id_type  INT(11)      NOT NULL AUTO_INCREMENT,
  nom_type VARCHAR(150) NOT NULL DEFAULT '',
  PRIMARY KEY (id_type)
)
  ENGINE = MyISAM;


INSERT INTO adslight_type VALUES (1, 'Zu verkaufen:');
INSERT INTO adslight_type VALUES (2, 'Suche:');
INSERT INTO adslight_type VALUES (3, 'Geben:');
INSERT INTO adslight_type VALUES (4, 'Umtauschen:');
INSERT INTO adslight_type VALUES (5, 'Mieten:');

CREATE TABLE adslight_price (
  id_price  INT(11)      NOT NULL AUTO_INCREMENT,
  nom_price VARCHAR(150) NOT NULL DEFAULT '',
  PRIMARY KEY (id_price)
)
  ENGINE = MyISAM;


INSERT INTO adslight_price VALUES (1, 'Nettopreis');
INSERT INTO adslight_price VALUES (2, 'Höchstpreis');
INSERT INTO adslight_price VALUES (3, 'übertragbar');

CREATE TABLE adslight_usure (
  id_usure  INT(11)      NOT NULL AUTO_INCREMENT,
  nom_usure VARCHAR(150) NOT NULL DEFAULT '',
  PRIMARY KEY (id_usure)
)
  ENGINE = MyISAM;


INSERT INTO adslight_usure VALUES (1, 'in gutem Zustand');
INSERT INTO adslight_usure VALUES (2, 'beschädigt');
INSERT INTO adslight_usure VALUES (3, 'in schlechtem Zustand');

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
