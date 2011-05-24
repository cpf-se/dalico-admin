
drop table if exists surveys cascade;

create table surveys (
	id	integer		not null,
	name	varchar(20)	not null,
	primary key (id)
);

copy surveys (id, name) from stdin;
1	Dalby 1
2	Dalby 2
3	Dalby 3
\.

