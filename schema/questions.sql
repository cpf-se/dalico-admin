
drop table if exists questions cascade;

create table questions (
	id	integer		not null,
	tag	varchar(10)	not null unique,
	limetag	varchar(20)	not null unique,
	text	text,
	primary key (id)
);

copy questions (id, tag, limetag) from stdin;
0	q0000	1X23X174
1	q1	1X1X1
3	q3	1X2X2
4	q4	1X2X3
5	q5	1X2X4
6	q6	1X2X5
7	q7	1X2X6
9	q9	1X3X7q9
10	q10	1X3X7q10
11	q11	1X3X7q11
12	q12	1X3X7q12
13	q13	1X3X7q13
14	q14	1X3X7q14
15	q15	1X3X7q15
16	q16	1X3X7q16
17	q17	1X3X7q17
18	q18	1X3X7q18
19	q19	1X3X7q19
20	q20	1X3X7q20
21	q21	1X3X7q21
23	q23	1X3X13q23
24	q24	1X3X13q24
26	q26	1X4X8q26
27	q27	1X4X8q27
28	q28	1X4X8q28
29	q29	1X4X8q29
30	q30	1X4X8q30
31	q31	1X5X9
32	q32	1X6X10
33	q33	1X7X11
34	q34	1X8X12
35	q35	1X9X14
36	q36	1X9X78
39	q39	1X9X79q39
40	q40	1X9X79q40
41	q41	1X9X79q41
42	q42	1X9X79q42
43	q43	1X9X79q43
44	q44	1X9X79q44
162	q162	1X22X70
163	q163	1X22X71
164	q164	1X22X72
165	q165	1X22X73
166	q166	1X22X74
167	q167	1X22X75
168	q168	1X22X76
49	q49	1X10X15
50	q50	1X10X16
52	q52	1X11X17
53	q53	1X11X18
54	q54	1X11X19
55	q55	1X11X20
57	q57	1X12X21
58	q58	1X12X22
59	q59	1X12X23
60	q60	1X12X24
66	q66	1X13X25
671	q67f	1X13X26
672	q67m	1X13X27
69	q69	1X14X28q69
70	q70	1X14X28q70
71	q71	1X14X28q71
72	q72	1X14X28q72
73	q73	1X14X28q73
74	q74	1X14X28q74
75	q75	1X14X28q75
76	q76	1X14X28q76
77	q77	1X14X28q77
78	q78	1X14X28q78
79	q79	1X14X28q79
80	q80	1X14X28q80
81	q81	1X14X28q81
82	q82	1X14X28q82
83	q83	1X14X28q83
84	q84	1X14X28q84
86	q86	1X15X29q86
87	q87	1X15X29q87
88	q88	1X15X29q88
89	q89	1X15X29q89
90	q90	1X15X29q90
91	q91	1X15X29q91
92	q92	1X15X29q92
93	q93	1X15X29q93
94	q94	1X15X29q94
95	q95	1X15X29q95
97	q97	1X16X30
98	q98	1X16X31
99	q99	1X16X32
100	q100	1X16X33
101	q101	1X16X34
102	q102	1X16X35
103	q103	1X16X36
104	q104	1X16X37
105	q105	1X16X38
106	q106	1X16X39
107	q107	1X16X40
108	q108	1X16X41
109	q109	1X16X42
110	q110	1X16X43
112	q112	1X17X44q112
113	q113	1X17X44q113
114	q114	1X17X44q114
115	q115	1X17X44q115
116	q116	1X17X44q116
117	q117	1X17X44q117
118	q118	1X17X44q118
119	q119	1X17X44q119
120	q120	1X17X44q120
121	q121	1X17X44q121
122	q122	1X17X44q122
123	q123	1X17X44q123
124	q124	1X17X44q124
125	q125	1X17X44q125
126	q126	1X17X44q126
128	q128	1X17X45q128
129	q129	1X17X45q129
130	q130	1X17X45q130
131	q131	1X17X45q131
133	q133	1X17X46q133
134	q134	1X17X46q134
135	q135	1X17X46q135
136	q136	1X17X46q136
137	q137	1X17X46q137
138	q138	1X17X46q138
139	q139	1X18X47
1401	q140a	1X18X77
1402	q140140	1X18X48a140
1403	q140141	1X18X48a141
1404	q140142	1X18X48a142
1405	q140143	1X18X48a143
1414	q141144	1X18X49a144
1415	q141145	1X18X49a145
1416	q141146	1X18X49a146
1417	q141147	1X18X49a147
142	q142	1X18X50
143	q143	1X18X51
144	q144	1X19X52
145146	q145146	1X20X60q146
145147	q145147	1X20X60q147
145148	q145148	1X20X60q148
145149	q145149	1X20X60q149
145150	q145150	1X20X60q150
145151	q145151	1X20X60q151
145152	q145152	1X20X60q152
145153	q145153	1X20X60q153
145154	q145154	1X20X60q154
146	q146	1X20X61
147	q147	1X20X62
148	q148	1X20X63
149	q149	1X20X64
150	q150	1X20X69
151	q151	1X20X65
152	q152	1X20X66
153	q153	1X20X67
154	q154	1X20X68
155	q155	1X20X53
156	q156	1X20X54
157	q157	1X20X55
158	q158	1X20X56
159	q159	1X21X57
160	q160	1X21X58
161	q161	1X21X59
\.
