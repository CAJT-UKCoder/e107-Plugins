CREATE TABLE roster_groups (
  roster_group_id int(10) NOT NULL auto_increment,
  roster_group_name varchar(255) NOT NULL,
  roster_group_order int(10) NOT NULL,
  PRIMARY KEY  (roster_group_id)
) TYPE=MyISAM;

CREATE TABLE roster_members (
  roster_member_id int(10) NOT NULL auto_increment,
  roster_member_name varchar(255) NOT NULL,
  roster_member_image varchar(255) NOT NULL,
  roster_member_enlisted varchar(255) NOT NULL,
  roster_member_rank varchar(255) NOT NULL,
  roster_member_ranknum int(10) NOT NULL,
  roster_member_rankdate varchar(255) NOT NULL,
  roster_member_group int(10) NOT NULL,
  roster_member_serial varchar(255) NOT NULL,
  roster_member_unit varchar(255) NOT NULL,
  roster_member_unitreport varchar(255) NOT NULL,
  roster_member_duty varchar(255) NOT NULL,
  roster_member_dutyreport varchar(255) NOT NULL,
  roster_member_status varchar(255) NOT NULL,
  roster_member_pfile text NOT NULL,
  roster_member_afile text NOT NULL,
  roster_member_location varchar(255) NOT NULL,
  roster_member_xfire varchar(255) NOT NULL,
  PRIMARY KEY (roster_member_id)
) TYPE=MyISAM;

CREATE TABLE roster_ranks (
  roster_rank_id int(10) NOT NULL auto_increment,
  roster_rank_name varchar(255) NOT NULL,
  roster_rank_grade varchar(255) NOT NULL,
  roster_rank_info text NOT NULL,
  roster_rank_order int(10) NOT NULL,
  PRIMARY KEY  (roster_rank_id)
) TYPE=MyISAM;

CREATE TABLE roster_awards (
  roster_award_id int(10) NOT NULL auto_increment,
  roster_award_name varchar(255) NOT NULL,
  roster_award_history text NOT NULL,
  roster_award_requirements text NOT NULL,
  roster_award_order int(10) NOT NULL,
  PRIMARY KEY  (roster_award_id)
) TYPE=MyISAM;



INSERT INTO roster_ranks (
  `roster_rank_id`,
  `roster_rank_name`,
  `roster_rank_grade`,
  `roster_rank_info`,
  `roster_rank_order`
) 

INSERT INTO roster_awards (
  `roster_award_id`,
  `roster_award_name`,
  `roster_award_history`,
  `roster_award_requirements`,
  `roster_award_order`
) 

