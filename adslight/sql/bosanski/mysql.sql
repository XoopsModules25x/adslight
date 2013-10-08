CREATE TABLE adslight_listing (
  lid int(15) NOT NULL auto_increment,
  cid int(15) NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  status int(3) NOT NULL default '0',
  expire char(3) NOT NULL default '',
  type varchar(15) NOT NULL default '',
  desctext text NOT NULL,
  tel varchar(15) NOT NULL default '',
  price decimal(20,2) NOT NULL default '0.00',
  typeprice varchar(15) NOT NULL default '',
  typeusure varchar(15) NOT NULL default '',
  date int(10) NOT NULL default '0',
  email varchar(100) NOT NULL default '',
  submitter varchar(60) NOT NULL default '',
  usid varchar(6) NOT NULL default '',
  town varchar(200) NOT NULL default '',
  country varchar(200) NOT NULL default '',
  contactby varchar(50) NOT NULL default '',
  premium char(3) NOT NULL default '',
  valid varchar(11) NOT NULL default '',
  photo varchar(100) NOT NULL default '0',
  hits int(11) NOT NULL default '0',
  item_rating double(6,4) NOT NULL default '0.0000',
  item_votes int(11) unsigned NOT NULL default '0',
  user_rating double(6,4) NOT NULL default '0.0000',
  user_votes int(11) unsigned NOT NULL default '0',
  comments int(11) unsigned NOT NULL default '0',
  remind int(11) NOT NULL default '0',
  PRIMARY KEY  (lid)
) ENGINE=MyISAM;

CREATE TABLE adslight_categories (
  cid int(11) NOT NULL auto_increment,
  pid int(5) unsigned NOT NULL default '0',
  title varchar(50) NOT NULL default '',
  cat_desc varchar(200) NOT NULL default '',
  cat_keywords varchar(1000) NOT NULL default '',
  img varchar(150) NOT NULL default 'default.png',
  ordre int(5) NOT NULL default '0',
  affprice int(5) NOT NULL default '1',
  cat_moderate int(5) NOT NULL default '1',
  moderate_subcat int(5) NOT NULL default '1',
  PRIMARY KEY  (cid)
) ENGINE=MyISAM;

INSERT INTO `adslight_categories` (`cid`, `pid`, `title`, `cat_desc`, `cat_keywords`, `img`, `ordre`, `affprice`, `cat_moderate`, `moderate_subcat`) VALUES
(1, 0, 'Auti / Motocikli', '', '', 'car.png', 0, 1, 1, 1),
(2, 0, 'Imanje', '', '', 'home.png', 0, 1, 1, 1),
(3, 0, 'Dobre poslovne', '', '', 'jewelry.png', 0, 1, 1, 1),
(4, 1, 'Auti', '', '', 'default.png', 0, 1, 1, 1),
(5, 1, 'Komunalne usluge', '', '', 'default.png', 0, 1, 1, 1),
(6, 1, 'Motocikli / Skuteri', '', '', 'default.png', 0, 1, 1, 1),
(7, 1, 'Caravanning', '', '', 'default.png', 0, 1, 1, 1),
(8, 1, 'Pribor', '', '', 'default.png', 0, 1, 1, 1),
(9, 2, 'Domovi Prodaja', '', '', 'default.png', 0, 1, 1, 1),
(10, 2, 'Prodaja Apartmani', '', '', 'default.png', 0, 1, 1, 1),
(11, 2, 'Iznajmljivanje za Odmor', '', '', 'default.png', 0, 1, 1, 1),
(12, 2, 'Iznajmljivanje apartmana', '', '', 'default.png', 0, 1, 1, 1),
(13, 2, 'Vacation Rentals', '', '', 'default.png', 0, 1, 1, 1),
(14, 3, 'Zabava', '', '', 'default.png', 0, 1, 1, 1),
(15, 3, 'Bricolage', '', '', 'default.png', 0, 1, 1, 1),
(16, 0, 'Računalo', '', '', 'computer.png', 0, 1, 1, 1),
(17, 0, 'Telefonija', '', '', 'telephony.png', 0, 1, 1, 1),
(18, 0, 'Sport i bicikala', '', '', 'mountain_bike.png', 0, 1, 1, 1),
(19, 0, 'Glazba', '', '', 'guitar.png', 0, 1, 1, 1),
(20, 19, 'Cd Glazbeni', '', '', 'default.png', 0, 1, 1, 1),
(21, 19, 'Dvd Glazbeni', '', '', 'default.png', 0, 1, 1, 1),
(22, 19, 'Glazbeni instrumenti', '', '', 'default.png', 0, 1, 1, 1),
(23, 16, 'Rezervni dijelovi', '', '', 'default.png', 0, 1, 1, 1),
(24, 16, 'Računala', '', '', 'default.png', 0, 1, 1, 1),
(25, 16, 'Igre', '', '', 'default.png', 0, 1, 1, 1),
(26, 16, 'Softver', '', '', 'default.png', 0, 1, 1, 1),
(27, 3, 'Dječji dodaci', '', '', 'default.png', 0, 1, 1, 1),
(28, 0, 'Aparati', '', '', 'appliances.png', 0, 1, 1, 1),
(29, 28, 'Televizori', '', '', 'default.png', 0, 1, 1, 1),
(30, 28, 'Prijenosni video', '', '', 'default.png', 0, 1, 1, 1),
(31, 28, 'Washers', '', '', 'default.png', 0, 1, 1, 1),
(32, 28, 'Peći', '', '', 'default.png', 0, 1, 1, 1),
(33, 18, 'Mountain bike', '', '', 'default.png', 0, 1, 1, 1),
(34, 18, 'Jahanje', '', '', 'default.png', 0, 1, 1, 1),
(35, 18, 'Odjeća', '', '', 'default.png', 0, 1, 1, 1),
(36, 18, 'Sport Cipele', '', '', 'default.png', 0, 1, 1, 1),
(37, 18, 'Ronjenje - Pribor', '', '', 'default.png', 0, 1, 1, 1);

CREATE TABLE adslight_type (
  id_type int(11) NOT NULL auto_increment,
  nom_type varchar(150) NOT NULL default '',
  PRIMARY KEY  (id_type)
) ENGINE=MyISAM;


INSERT INTO adslight_type VALUES (1,'Prodaja:');
INSERT INTO adslight_type VALUES (2,'Pretraživanje:');
INSERT INTO adslight_type VALUES (3,'Dati:');
INSERT INTO adslight_type VALUES (4,'Razmjena:');
INSERT INTO adslight_type VALUES (5,'Najam:');

CREATE TABLE adslight_price (
  id_price int(11) NOT NULL auto_increment,
  nom_price varchar(150) NOT NULL default '',
  PRIMARY KEY  (id_price)
) ENGINE=MyISAM;


INSERT INTO adslight_price VALUES (1,'Farma');
INSERT INTO adslight_price VALUES (2,'Maksimum');
INSERT INTO adslight_price VALUES (3,'Po dogovoru');

CREATE TABLE adslight_usure (
  id_usure int(11) NOT NULL auto_increment,
  nom_usure varchar(150) NOT NULL default '',
  PRIMARY KEY  (id_usure)
) ENGINE=MyISAM;


INSERT INTO adslight_usure VALUES (1,'u vrlo dobrom stanju');
INSERT INTO adslight_usure VALUES (2,'državnog prosjeka');
INSERT INTO adslight_usure VALUES (3,'razmažen');
INSERT INTO adslight_usure VALUES (4,'lošem stanju');

CREATE TABLE adslight_ip_log (
  ip_id int(11) NOT NULL auto_increment,
  lid int(11) NOT NULL default '0',
  date varchar(25) default NULL,
  submitter varchar(60) NOT NULL default '',
  ipnumber varchar(150) NOT NULL default '',
  email varchar(100) NOT NULL default '',
  PRIMARY KEY  (`ip_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;

#
# Table structure for table `adslight_votedata`
#

CREATE TABLE adslight_item_votedata (
  ratingid int(11) unsigned NOT NULL auto_increment,
  lid int(11) unsigned NOT NULL default '0',
  ratinguser int(11) unsigned NOT NULL default '0',
  rating tinyint(3) unsigned NOT NULL default '0',
  ratinghostname varchar(60) NOT NULL default '',
  ratingtimestamp int(10) NOT NULL default '0',
  PRIMARY KEY  (ratingid),
  KEY ratinguser (ratinguser),
  KEY ratinghostname (ratinghostname)
) ENGINE=MyISAM;

#
# Table structure for table `adslight_votedata`
#

CREATE TABLE adslight_user_votedata (
  ratingid int(11) unsigned NOT NULL auto_increment,
  usid int(11) unsigned NOT NULL default '0',
  ratinguser int(11) unsigned NOT NULL default '0',
  rating tinyint(3) unsigned NOT NULL default '0',
  ratinghostname varchar(60) NOT NULL default '',
  ratingtimestamp int(10) NOT NULL default '0',
  PRIMARY KEY  (ratingid),
  KEY ratinguser (ratinguser),
  KEY ratinghostname (ratinghostname)
) ENGINE=MyISAM;

CREATE TABLE adslight_pictures (
  cod_img int(11) NOT NULL auto_increment,
  title varchar(255) NOT NULL,
  date_added int(10) NOT NULL default '0',
  date_modified int(10) NOT NULL default '0',
  lid int(11) NOT NULL default '0',
  uid_owner varchar(50) NOT NULL,
  url text NOT NULL,
  PRIMARY KEY  (cod_img)
) ENGINE=MyISAM  ;

CREATE TABLE adslight_replies (
  r_lid int(11) NOT NULL auto_increment,
  lid int(11) NOT NULL default '0',
  title varchar(50) NOT NULL default '',
  date int(10) NOT NULL default '0',
  submitter varchar(60) NOT NULL default '',
  message text NOT NULL,
  tele varchar(15) NOT NULL default '',
  email varchar(100) NOT NULL default '',
  r_usid int(11) NOT NULL default '0',
  PRIMARY KEY  (r_lid)
) ENGINE=MyISAM;