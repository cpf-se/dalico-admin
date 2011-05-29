
drop table if exists surveys_questions cascade;

create table surveys_questions (
	survey		integer		not null,
	question	integer		not null,
	ord		integer		not null,
	primary key (survey, question),
	foreign key (survey) references surveys (id),
	foreign key (question) references questions (id)
);

copy surveys_questions (survey, question, ord) from stdin;
1	0	1
1	1	2
1	3	3
1	4	4
1	5	5
1	6	6
1	7	7
1	9	8
1	10	9
1	11	10
1	12	11
1	13	12
1	14	13
1	15	14
1	16	15
1	17	16
1	18	17
1	19	18
1	20	19
1	21	20
1	23	21
1	24	22
1	26	23
1	27	24
1	28	25
1	29	26
1	30	27
1	31	28
1	32	29
1	33	30
1	34	31
1	35	32
1	36	33
1	39	34
1	40	35
1	41	36
1	42	37
1	43	38
1	44	39
1	162	40
1	163	41
1	164	42
1	165	43
1	166	44
1	167	45
1	168	46
1	49	47
1	50	48
1	52	49
1	53	50
1	54	51
1	55	52
1	57	53
1	58	54
1	59	55
1	60	56
1	66	57
1	671	58
1	672	59
1	69	60
1	70	61
1	71	62
1	72	63
1	73	64
1	74	65
1	75	66
1	76	67
1	77	68
1	78	69
1	79	70
1	80	71
1	81	72
1	82	73
1	83	74
1	84	75
1	86	76
1	87	77
1	88	78
1	89	79
1	90	80
1	91	81
1	92	82
1	93	83
1	94	84
1	95	85
1	97	86
1	98	87
1	99	88
1	100	89
1	101	90
1	102	91
1	103	92
1	104	93
1	105	94
1	106	95
1	107	96
1	108	97
1	109	98
1	110	99
1	112	100
1	113	101
1	114	102
1	115	103
1	116	104
1	117	105
1	118	106
1	119	107
1	120	108
1	121	109
1	122	110
1	123	111
1	124	112
1	125	113
1	126	114
1	128	115
1	129	116
1	130	117
1	131	118
1	133	119
1	134	120
1	135	121
1	136	122
1	137	123
1	138	124
1	139	125
1	1401	126
1	1402	127
1	1403	128
1	1404	129
1	1405	130
1	1414	131
1	1415	132
1	1416	133
1	1417	134
1	142	135
1	143	136
1	144	137
1	145146	138
1	145147	139
1	145148	140
1	145149	141
1	145150	142
1	145151	143
1	145152	144
1	145153	145
1	145154	146
1	146	147
1	147	148
1	148	149
1	149	150
1	150	151
1	151	152
1	152	153
1	153	154
1	154	155
1	155	156
1	156	157
1	157	158
1	158	159
1	159	160
1	160	161
1	161	162
\.