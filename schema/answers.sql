
drop table if exists answers cascade;

create table answers (
	id	integer		not null,
	tag	varchar(10)	not null unique,
	text	text,
	short	text,
	value	integer,
	primary key (id)
);

copy answers (id, tag) from stdin;
-1	a0001
0	a0002
1	A1
2	A2
3	A3
4	A4
5	A5
6	a6
7	a7
8	a8
9	a9
10	a10
11	a11
12	a12
13	a13
14	a14
15	a15
16	a16
17	a17
18	a18
19	a19
20	a20
21	a21
22	a22
23	a23
24	a24
25	a25
26	a26
27	a27
28	a28
29	a29
30	a30
31	a31
32	a32
33	a33
34	a34
35	a35
36	a36
37	a37
38	a38
39	a39
40	a40
41	a41
42	a42
43	a43
44	a44
45	a45
46	a46
47	a47
471	a47a
472	a47b
48	a48
49	a49
50	a50
51	a51
52	a52
53	a53
54	a54
55	a55
56	a56
57	a57
58	a58
59	a59
60	a60
61	a61
62	a62
63	a63
64	a64
65	a65
66	a66
67	a67
68	a68
69	a69
70	a70
71	a71
72	a72
73	a73
74	a74
75	a75
76	a76
77	a77
78	a78
79	a79
80	a80
81	a81
82	a82
83	a83
84	a84
85	a85
86	a86
87	a87
88	a88
89	a89
90	a90
91	a91
92	a92
93	a93
94	a94
95	a95
96	a96
97	a97
98	a98
99	a99
100	a100
101	a101
102	a102
103	a103
104	a104
105	a105
106	a106
107	a107
108	a108
109	a109
110	a110
111	a111
112	a112
113	a113
114	a114
115	a115
116	a116
117	a117
118	a118
119	a119
120	a120
121	a121
122	a122
123	a123
124	a124
125	a125
126	a126
127	a127
128	a128
129	a129
130	a130
131	a131
1321	a1321
1322	a1322
1323	a1323
1334	a1334
1335	a1335
1336	a1336
1347	a1347
135	a135
136	a136
137	a137
138	a138
139	a139
188	a188
189	a189
190	a190
191	a191
192	a192
193	a193
194	a194
195	a195
196	a196
197	a197
198	a198
199	a199
200	a200
201	a201
202	a202
203	a203
204	a204
205	a205
206	a206
207	a207
208	a208
209	a209
210	a210
211	a211
212	a212
213	a213
214	a214
215	a215
216	a216
217	a217
218	a218
219	a219
220	a220
\.

