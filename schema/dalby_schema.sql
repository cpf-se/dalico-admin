-- deps:
--	- users
--	- surveys

drop table if exists rdet;
drop table if exists responses;
drop table if exists patients_surveys;
drop table if exists patients;
drop table if exists lists;
drop table if exists idops;
drop table if exists vcs;

create table vcs (
	id	serial,
	name	varchar(32)	not null unique,
	primary key (id)
);

copy vcs (name) from stdin;
Dalby
Bara
\.

create table idops (
	id	serial,
	usr	integer,
	name	varchar(32),
	vc	integer		not null,
	primary key (id),
	foreign key (usr)	references users (id)	on delete restrict on update cascade,
	foreign key (vc)	references vcs (id)	on delete restrict on update cascade
);

insert into idops (name, vc) values
('Olsson', (select id from vcs where name = 'Dalby')),
('Hallberg', (select id from vcs where name = 'Bara')),
('Hedberg', (select id from vcs where name = 'Bara')),
('Nilsson', (select id from vcs where name = 'Bara'));

create table lists (
	id	serial,
	idop	integer,
	num	integer		not null,
	primary key (id),
	foreign key (idop)	references idops (id)	on delete restrict on update cascade,
	unique (idop, num)
);

insert into lists (idop, num) values
((select id from idops where name = 'Olsson'), 1),
((select id from idops where name = 'Hallberg'), 1),
((select id from idops where name = 'Nilsson'), 1),
((select id from idops where name = 'Hedberg'), 1),
((select id from idops where name = 'Olsson'), 2);


create table patients (
	token	varchar(10),
	list	integer,
	primary key (token),
	foreign key (list)	references lists (id)	on delete restrict on update cascade
);

create table patients_surveys (
	patient	varchar(10),
	survey	integer,
	primary key (patient, survey),
	foreign key (patient)	references patients (token)	on delete restrict on update cascade,
	foreign key (survey)	references surveys (id)		on delete restrict on update cascade
);

create table responses (
	id	serial,
	stamp	timestamp with time zone,
	patient	varchar(10),
	survey	integer,
	primary key (id),
	foreign key (patient, survey)	references patients_surveys (patient, survey)	on delete restrict on update cascade,
	unique (stamp, patient, survey)
);

create table rdet (
	id		serial,
	response	integer,
	question	integer,
	answer		integer,
	primary key (id),
	foreign key (response)		references responses (id)			on delete restrict on update cascade,
	foreign key (question, answer)	references questions_answers (question, answer)	on delete restrict on update cascade
);

