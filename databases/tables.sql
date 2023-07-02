create table users(
	id int(10) not null primary key auto_increment,
	username varchar(100),
	password varchar(100)
);


create table ads(
	id int(10) not null primary key auto_increment,
	ad_name varchar(100),
	prop_name varchar(100)
);

create table properties(
	id int(10) not null primary key auto_increment,
	prop_reference varchar(100),
	prop_name varchar(100)
);