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
  typecondition   VARCHAR(15)      NOT NULL DEFAULT '',
  date_created INT(11) UNSIGNED NOT NULL DEFAULT 0,
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
  cat_order       INT(5)          NOT NULL DEFAULT '0',
  affprice        INT(5)          NOT NULL DEFAULT '1',
  cat_moderate    INT(5)          NOT NULL DEFAULT '1',
  moderate_subcat INT(5)          NOT NULL DEFAULT '1',
  PRIMARY KEY (cid)
)
  ENGINE = MyISAM;

INSERT INTO `adslight_categories` (`cid`, `pid`, `title`, `cat_desc`, `cat_keywords`, `img`, `cat_order`, `affprice`, `cat_moderate`, `moderate_subcat`) VALUES
  (1, 0, 'Auto / Moto', '', '', 'car.png', 0, 1, 1, 1),
  (2, 0, 'Inmobiliario', '', '', 'home.png', 0, 1, 1, 1),
  (3, 0, 'Un buen negocio', '', '', 'jewelry.png', 0, 1, 1, 1),
  (4, 1, 'Coches', '', '', 'default.png', 0, 1, 1, 1),
  (5, 1, 'Turismos Furgonetas', '', '', 'default.png', 0, 1, 1, 1),
  (6, 1, 'Motocicletas / ciclomotores', '', '', 'default.png', 0, 1, 1, 1),
  (7, 1, 'Caravanas', '', '', 'default.png', 0, 1, 1, 1),
  (8, 1, 'Accesorios', '', '', 'default.png', 0, 1, 1, 1),
  (9, 2, 'Venta de Casas', '', '', 'default.png', 0, 1, 1, 1),
  (10, 2, 'Venta de apartamentos', '', '', 'default.png', 0, 1, 1, 1),
  (11, 2, 'Casas de Alquiler', '', '', 'default.png', 0, 1, 1, 1),
  (12, 2, 'Alquiler de Apartamentos', '', '', 'default.png', 0, 1, 1, 1),
  (13, 2, 'Alquiler de vacaciones', '', '', 'default.png', 0, 1, 1, 1),
  (14, 3, 'Aficiones', '', '', 'default.png', 0, 1, 1, 1),
  (15, 3, 'Bricolaje', '', '', 'default.png', 0, 1, 1, 1),
  (16, 0, 'Informática', '', '', 'computer.png', 0, 1, 1, 1),
  (17, 0, 'Moviles', '', '', 'telephony.png', 0, 1, 1, 1),
  (18, 0, 'Deportes y bicicletas', '', '', 'mountain_bike.png', 0, 1, 1, 1),
  (19, 0, 'Música', '', '', 'guitar.png', 0, 1, 1, 1),
  (20, 19, 'Cd Música', '', '', 'default.png', 0, 1, 1, 1),
  (21, 19, 'Dvd Música', '', '', 'default.png', 0, 1, 1, 1),
  (22, 19, 'Instrumentos musicales', '', '', 'default.png', 0, 1, 1, 1),
  (23, 16, 'Piezas', '', '', 'default.png', 0, 1, 1, 1),
  (24, 16, 'Computadoras', '', '', 'default.png', 0, 1, 1, 1),
  (25, 16, 'Juegos', '', '', 'default.png', 0, 1, 1, 1),
  (26, 16, 'Software ', '', '', 'default.png', 0, 1, 1, 1),
  (27, 3, 'Accesorios infantiles', '', '', 'default.png', 0, 1, 1, 1),
  (28, 0, 'Electrodomésticos', '', '', 'appliances.png', 0, 1, 1, 1),
  (29, 28, 'Televisión', '', '', 'default.png', 0, 1, 1, 1),
  (30, 28, 'Videojuegos', '', '', 'default.png', 0, 1, 1, 1),
  (31, 28, 'Lavadoras', '', '', 'default.png', 0, 1, 1, 1),
  (32, 28, 'Hornos', '', '', 'default.png', 0, 1, 1, 1),
  (33, 18, 'Mtb', '', '', 'default.png', 0, 1, 1, 1),
  (34, 18, 'Ecuestre', '', '', 'default.png', 0, 1, 1, 1),
  (35, 18, 'Ropa', '', '', 'default.png', 0, 1, 1, 1),
  (36, 18, 'Zapatos Deportes', '', '', 'default.png', 0, 1, 1, 1),
  (37, 18, 'Accesorios de buceo', '', '', 'default.png', 0, 1, 1, 1);

CREATE TABLE adslight_type (
  id_type  INT(11)      NOT NULL AUTO_INCREMENT,
  nom_type VARCHAR(150) NOT NULL DEFAULT '',
  PRIMARY KEY (id_type)
)
  ENGINE = MyISAM;


INSERT INTO adslight_type VALUES (1, 'Venta:');
INSERT INTO adslight_type VALUES (2, 'Búsqueda:');
INSERT INTO adslight_type VALUES (3, 'Dar:');
INSERT INTO adslight_type VALUES (4, 'Intercambio:');
INSERT INTO adslight_type VALUES (5, 'Alquiler:');


CREATE TABLE adslight_price (
  id_price  INT(11)      NOT NULL AUTO_INCREMENT,
  nom_price VARCHAR(150) NOT NULL DEFAULT '',
  PRIMARY KEY (id_price)
)
  ENGINE = MyISAM;


INSERT INTO adslight_price VALUES (1, 'Precio fijo');
INSERT INTO adslight_price VALUES (2, 'Precio Máximo');
INSERT INTO adslight_price VALUES (3, 'Precio negociable');

CREATE TABLE adslight_user (
  id_user  INT(11)      NOT NULL AUTO_INCREMENT,
  nom_user VARCHAR(150) NOT NULL DEFAULT '',
  PRIMARY KEY (id_user)
)
  ENGINE = MyISAM;


INSERT INTO adslight_user VALUES (1, 'En muy buenas condiciones');
INSERT INTO adslight_user VALUES (2, 'Regular');
INSERT INTO adslight_user VALUES (3, 'Está dañado');
INSERT INTO adslight_user VALUES (4, 'En mal estado');

CREATE TABLE adslight_ip_log (
  ip_id     INT(11)      NOT NULL AUTO_INCREMENT,
  lid       INT(11)      NOT NULL DEFAULT '0',
  date_created INT(11) UNSIGNED NOT NULL DEFAULT 0,
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
  date_created INT(11) UNSIGNED NOT NULL DEFAULT 0,
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
  date_created INT(11) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (ratingid),
  KEY ratinguser (ratinguser),
  KEY ratinghostname (ratinghostname)
)
  ENGINE = MyISAM;

CREATE TABLE adslight_pictures (
  cod_img       INT(11)      NOT NULL AUTO_INCREMENT,
  title         VARCHAR(255) NOT NULL,
  date_created    INT(10)      NOT NULL DEFAULT '0',
  date_updated INT(10)      NOT NULL DEFAULT '0',
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
  date_created INT(11) UNSIGNED NOT NULL DEFAULT 0,
  submitter VARCHAR(60)  NOT NULL DEFAULT '',
  message   TEXT         NOT NULL,
  tele      VARCHAR(15)  NOT NULL DEFAULT '',
  email     VARCHAR(100) NOT NULL DEFAULT '',
  r_usid    INT(11)      NOT NULL DEFAULT '0',
  PRIMARY KEY (r_lid)
)
  ENGINE = MyISAM;
