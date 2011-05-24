
drop table if exists questions_answers restrict;

create table questions_answers (
	question	integer		not null,
	answer		integer		not null,
	ord		integer		not null,
	primary key (question, answer),
	foreign key (question) references questions (id),
	foreign key (answer)   references answers   (id)
);

copy questions_answers (question, answer, ord) from stdin;
0	-1	1
0	0	2
1	1	1
1	2	2
1	3	3
1	4	4
1	5	5
3	6	1
3	7	2
3	8	3
4	9	1
4	10	2
4	11	3
5	12	1
5	13	2
5	14	3
6	15	1
6	16	2
6	17	3
7	18	1
7	19	2
7	20	3
9	21	1
9	22	2
9	23	3
10	21	1
10	22	2
10	23	3
11	21	1
11	22	2
11	23	3
12	21	1
12	22	2
12	23	3
13	21	1
13	22	2
13	23	3
14	21	1
14	22	2
14	23	3
15	21	1
15	22	2
15	23	3
16	21	1
16	22	2
16	23	3
17	21	1
17	22	2
17	23	3
18	21	1
18	22	2
18	23	3
19	21	1
19	22	2
19	23	3
20	21	1
20	22	2
20	23	3
21	21	1
21	22	2
21	23	3
23	21	1
23	22	2
23	23	3
24	21	1
24	22	2
24	23	3
26	24	1
26	25	2
26	21	3
27	24	1
27	25	2
27	21	3
28	24	1
28	25	2
28	21	3
29	24	1
29	25	2
29	21	3
30	24	1
30	25	2
30	21	3
31	26	1
31	27	2
31	28	3
32	29	1
32	30	2
32	31	3
32	32	4
32	33	5
33	34	1
33	35	2
33	36	3
34	37	1
34	38	2
34	39	3
34	40	4
34	41	5
34	42	6
34	43	7
35	44	1
35	45	2
35	46	3
35	471	4
35	472	5
35	48	6
36	44	1
36	45	2
36	46	3
36	47	4
36	50	5
36	51	6
36	52	7
39	53	1
39	54	2
39	55	3
39	56	4
40	53	1
40	54	2
40	55	3
40	56	4
41	53	1
41	54	2
41	55	3
41	56	4
42	53	1
42	54	2
42	55	3
42	56	4
43	53	1
43	54	2
43	55	3
43	56	4
44	53	1
44	54	2
44	55	3
44	56	4
162	188	1
162	189	2
162	190	3
162	191	4
162	192	5
162	193	6
162	194	7
162	195	8
163	196	1
163	197	2
163	198	3
163	199	4
163	200	5
163	201	6
163	202	7
163	203	8
163	204	9
163	205	10
164	188	1
164	189	2
164	190	3
164	191	4
164	192	5
164	193	6
164	194	7
164	195	8
165	196	1
165	197	2
165	198	3
165	199	4
165	200	5
165	201	6
165	202	7
165	203	8
165	204	9
165	205	10
166	188	1
166	189	2
166	190	3
166	191	4
166	192	5
166	193	6
166	194	7
166	195	8
167	196	1
167	197	2
167	198	3
167	199	4
167	200	5
167	201	6
167	202	7
167	203	8
167	204	9
167	205	10
168	206	1
168	207	2
168	208	3
168	209	4
168	210	5
168	211	6
168	212	7
168	213	8
168	214	9
168	215	10
168	216	11
168	217	12
168	218	13
168	219	14
168	220	15
168	205	16
49	57	1
49	21	2
50	44	1
50	45	2
50	46	3
50	47	4
50	50	5
50	51	6
50	52	7
52	58	1
52	59	2
52	60	3
52	61	4
53	58	1
53	59	2
53	60	3
53	61	4
54	62	1
54	63	2
54	64	3
54	65	4
55	66	1
55	67	2
55	60	3
55	61	4
57	68	1
57	69	2
57	70	3
57	71	4
57	72	5
58	73	1
58	74	2
58	75	3
59	76	1
59	77	2
59	78	3
59	79	4
59	80	5
60	81	1
60	82	2
60	83	3
66	84	1
66	85	2
66	86	3
66	87	4
66	88	5
671	89	1
671	90	2
671	91	3
671	92	4
671	93	5
672	89	1
672	90	2
672	91	3
672	92	4
672	93	5
69	94	1
69	95	2
69	96	3
69	97	4
69	98	5
70	94	1
70	95	2
70	96	3
70	97	4
70	98	5
71	94	1
71	95	2
71	96	3
71	97	4
71	98	5
72	94	1
72	95	2
72	96	3
72	97	4
72	98	5
73	94	1
73	95	2
73	96	3
73	97	4
73	98	5
74	94	1
74	95	2
74	96	3
74	97	4
74	98	5
75	94	1
75	95	2
75	96	3
75	97	4
75	98	5
76	94	1
76	95	2
76	96	3
76	97	4
76	98	5
77	94	1
77	95	2
77	96	3
77	97	4
77	98	5
78	94	1
78	95	2
78	96	3
78	97	4
78	98	5
79	94	1
79	95	2
79	96	3
79	97	4
79	98	5
80	94	1
80	95	2
80	96	3
80	97	4
80	98	5
81	94	1
81	95	2
81	96	3
81	97	4
81	98	5
82	94	1
82	95	2
82	96	3
82	97	4
82	98	5
83	94	1
83	95	2
83	96	3
83	97	4
83	98	5
84	94	1
84	95	2
84	96	3
84	97	4
84	98	5
86	99	1
86	100	2
86	101	3
86	102	4
87	99	1
87	100	2
87	101	3
87	102	4
88	99	1
88	100	2
88	101	3
88	102	4
89	99	1
89	100	2
89	101	3
89	102	4
90	99	1
90	100	2
90	101	3
90	102	4
91	99	1
91	100	2
91	101	3
91	102	4
92	99	1
92	100	2
92	101	3
92	102	4
93	99	1
93	100	2
93	101	3
93	102	4
94	99	1
94	100	2
94	101	3
94	102	4
95	99	1
95	100	2
95	101	3
95	102	4
97	103	1
97	104	2
97	105	3
97	106	4
98	107	1
98	108	2
98	109	3
98	94	4
99	110	1
99	111	2
99	112	3
99	106	4
100	113	1
100	114	2
100	115	3
100	89	4
101	103	1
101	116	2
101	105	3
101	117	4
102	89	1
102	118	2
102	119	3
102	103	4
103	120	1
103	121	2
103	118	3
103	89	4
104	122	1
104	104	2
104	119	3
104	89	4
105	89	1
105	119	2
105	116	3
105	123	4
106	124	1
106	125	2
106	126	3
106	106	4
107	123	1
107	116	2
107	118	3
107	106	4
108	127	1
108	128	2
108	129	3
108	130	4
109	123	1
109	116	2
109	118	3
109	89	4
110	104	1
110	119	2
110	118	3
110	131	4
112	1321	1
112	1322	2
112	1323	3
112	1334	4
112	1335	5
112	1336	6
112	1347	7
113	1321	1
113	1322	2
113	1323	3
113	1334	4
113	1335	5
113	1336	6
113	1347	7
114	1321	1
114	1322	2
114	1323	3
114	1334	4
114	1335	5
114	1336	6
114	1347	7
115	1321	1
115	1322	2
115	1323	3
115	1334	4
115	1335	5
115	1336	6
115	1347	7
116	1321	1
116	1322	2
116	1323	3
116	1334	4
116	1335	5
116	1336	6
116	1347	7
117	1321	1
117	1322	2
117	1323	3
117	1334	4
117	1335	5
117	1336	6
117	1347	7
118	1321	1
118	1322	2
118	1323	3
118	1334	4
118	1335	5
118	1336	6
118	1347	7
119	1321	1
119	1322	2
119	1323	3
119	1334	4
119	1335	5
119	1336	6
119	1347	7
120	1321	1
120	1322	2
120	1323	3
120	1334	4
120	1335	5
120	1336	6
120	1347	7
121	1321	1
121	1322	2
121	1323	3
121	1334	4
121	1335	5
121	1336	6
121	1347	7
122	1321	1
122	1322	2
122	1323	3
122	1334	4
122	1335	5
122	1336	6
122	1347	7
123	1321	1
123	1322	2
123	1323	3
123	1334	4
123	1335	5
123	1336	6
123	1347	7
124	1321	1
124	1322	2
124	1323	3
124	1334	4
124	1335	5
124	1336	6
124	1347	7
125	1321	1
125	1322	2
125	1323	3
125	1334	4
125	1335	5
125	1336	6
125	1347	7
126	1321	1
126	1322	2
126	1323	3
126	1334	4
126	1335	5
126	1336	6
126	1347	7
128	1321	1
128	1322	2
128	1323	3
128	1334	4
128	1335	5
128	1336	6
128	1347	7
129	1321	1
129	1322	2
129	1323	3
129	1334	4
129	1335	5
129	1336	6
129	1347	7
130	1321	1
130	1322	2
130	1323	3
130	1334	4
130	1335	5
130	1336	6
130	1347	7
131	1321	1
131	1322	2
131	1323	3
131	1334	4
131	1335	5
131	1336	6
131	1347	7
133	1321	1
133	1322	2
133	1323	3
133	1334	4
133	1335	5
133	1336	6
133	1347	7
134	1321	1
134	1322	2
134	1323	3
134	1334	4
134	1335	5
134	1336	6
134	1347	7
135	1321	1
135	1322	2
135	1323	3
135	1334	4
135	1335	5
135	1336	6
135	1347	7
136	1321	1
136	1322	2
136	1323	3
136	1334	4
136	1335	5
136	1336	6
136	1347	7
137	1321	1
137	1322	2
137	1323	3
137	1334	4
137	1335	5
137	1336	6
137	1347	7
138	1321	1
138	1322	2
138	1323	3
138	1334	4
138	1335	5
138	1336	6
138	1347	7
\.

