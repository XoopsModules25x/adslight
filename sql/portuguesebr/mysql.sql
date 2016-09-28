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
  (1, 0, 'Automotive / Motorcycle', '', '', 'car.png', 0, 1, 1, 1),
  (2, 0, 'Real Estate', '', '', 'home.png', 0, 1, 1, 1),
  (3, 0, 'Good Business', '', '', 'jewelry.png', 0, 1, 1, 1),
  (4, 1, 'Cars', '', '', 'default.png', 0, 1, 1, 1),
  (5, 1, 'Utility car', '', '', 'default.png', 0, 1, 1, 1),
  (6, 1, 'Motorcycles / scooters', '', '', 'default.png', 0, 1, 1, 1),
  (7, 1, 'Caravanning', '', '', 'default.png', 0, 1, 1, 1),
  (8, 1, 'Accessories', '', '', 'default.png', 0, 1, 1, 1),
  (9, 2, 'Sales Properties', '', '', 'default.png', 0, 1, 1, 1),
  (10, 2, 'Sales Apartments', '', '', 'default.png', 0, 1, 1, 1),
  (11, 2, 'Rental Homes', '', '', 'default.png', 0, 1, 1, 1),
  (12, 2, 'Apartments Rentals', '', '', 'default.png', 0, 1, 1, 1),
  (13, 2, 'Vacation Rentals', '', '', 'default.png', 0, 1, 1, 1),
  (14, 3, 'Hobbies', '', '', 'default.png', 0, 1, 1, 1),
  (15, 3, 'Bricolage', '', '', 'default.png', 0, 1, 1, 1),
  (16, 0, 'Computer', '', '', 'computer.png', 0, 1, 1, 1),
  (17, 0, 'Telephony', '', '', 'telephony.png', 0, 1, 1, 1),
  (18, 0, 'Sports and Bicycle', '', '', 'mountain_bike.png', 0, 1, 1, 1),
  (19, 0, 'Music', '', '', 'guitar.png', 0, 1, 1, 1),
  (20, 19, 'Cd music', '', '', 'default.png', 0, 1, 1, 1),
  (21, 19, 'Dvd music', '', '', 'default.png', 0, 1, 1, 1),
  (22, 19, 'Musical instruments', '', '', 'default.png', 0, 1, 1, 1),
  (23, 16, 'Parts', '', '', 'default.png', 0, 1, 1, 1),
  (24, 16, 'Computers', '', '', 'default.png', 0, 1, 1, 1),
  (25, 16, 'Games', '', '', 'default.png', 0, 1, 1, 1),
  (26, 16, 'Software', '', '', 'default.png', 0, 1, 1, 1),
  (27, 3, 'Nursery accessories', '', '', 'default.png', 0, 1, 1, 1),
  (28, 0, 'Appliances', '', '', 'appliances.png', 0, 1, 1, 1),
  (29, 28, 'TVs', '', '', 'default.png', 0, 1, 1, 1),
  (30, 28, 'Videos Players', '', '', 'default.png', 0, 1, 1, 1),
  (31, 28, 'Washing Machines', '', '', 'default.png', 0, 1, 1, 1),
  (32, 28, 'Ovens', '', '', 'default.png', 0, 1, 1, 1),
  (33, 18, 'Mtb', '', '', 'default.png', 0, 1, 1, 1),
  (34, 18, 'Equestrian', '', '', 'default.png', 0, 1, 1, 1),
  (35, 18, 'Clothes', '', '', 'default.png', 0, 1, 1, 1),
  (36, 18, 'Sport Shoes', '', '', 'default.png', 0, 1, 1, 1),
  (37, 18, 'Diving - Accessories', '', '', 'default.png', 0, 1, 1, 1);

CREATE TABLE adslight_type (
  id_type  INT(11)      NOT NULL AUTO_INCREMENT,
  nom_type VARCHAR(150) NOT NULL DEFAULT '',
  PRIMARY KEY (id_type)
)
  ENGINE = MyISAM;


INSERT INTO adslight_type VALUES (1, 'For Sale:');
INSERT INTO adslight_type VALUES (2, 'Search:');
INSERT INTO adslight_type VALUES (3, 'Give:');
INSERT INTO adslight_type VALUES (4, 'Exchange:');


CREATE TABLE adslight_price (
  id_price  INT(11)      NOT NULL AUTO_INCREMENT,
  nom_price VARCHAR(150) NOT NULL DEFAULT '',
  PRIMARY KEY (id_price)
)
  ENGINE = MyISAM;


INSERT INTO adslight_price VALUES (1, 'Farm');
INSERT INTO adslight_price VALUES (2, 'Max');
INSERT INTO adslight_price VALUES (3, 'Negotiable');

CREATE TABLE adslight_usure (
  id_usure  INT(11)      NOT NULL AUTO_INCREMENT,
  nom_usure VARCHAR(150) NOT NULL DEFAULT '',
  PRIMARY KEY (id_usure)
)
  ENGINE = MyISAM;


INSERT INTO adslight_usure VALUES (1, 'In very good condition');
INSERT INTO adslight_usure VALUES (2, 'Middle condition');
INSERT INTO adslight_usure VALUES (3, 'Damaged');
INSERT INTO adslight_usure VALUES (4, 'In Poor Condition');

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
