<?php

require_once 'pg_connect_string.php';

function _PGSQL($query, &$result) {
	$c = pg_connect(PG_CONNECT_STRING) or die("Error connecting to PostgreSQL: " . pg_last_error($c));
	$r = pg_query($c, $query) or die("Error querying PostgreSQL: " . pg_last_error($c) . "\nquery was:\n" . $query);
	$s = 0;
	while ($row = pg_fetch_array($r)) {
		$s += 1;
		array_push($result, $row);
	}
	return $s;
}

function _IMPLODE($what, $glue = ', ') {
	if (is_array($what)) return implode($glue, $what);
	else return $what;
}

function _SELECT($what = 'COUNT(*)') {
	return sprintf("SELECT %s", _IMPLODE($what));
}

function _FROM($from) {
	return sprintf(" FROM %s", _IMPLODE($from));
}

function _JOIN($from, $on, $type = 'INNER') {
	return " " . $type . ' JOIN ' . $from . ' ON ' . $on;
}

function _WHERE($where) {
	return sprintf(" WHERE %s", $where);
}

function _INSERT($table, $vars, $vals) {
	return sprintf("INSERT INTO %s %s VALUES\n%s\nRETURNING *", $table, $vars, $vals);
}

function _UPDATE($table, $attributes, $where) {
	$ASSIGNMENTS = array();
	foreach ($attributes as $attr => $val) {
		$ASSIGNMENTS[] = $attr . ' = ' . _Q($val);
	}
	return sprintf("UPDATE %s SET %s WHERE %s RETURNING *", $table, implode($ASSIGNMENTS), $where);
}

function _AND($what) {
	return _IMPLODE($what, ' AND ');
}

function _OR($what) {
	return _IMPLODE($what, ' OR ');
}

function _ORDERBY($what) {
	return sprintf(" ORDER BY %s ", _IMPLODE($what));
}

function _Q($str) {
	return sprintf("'%s'", _IMPLODE($str));
}

function _P($str) {
	return sprintf("(%s)", _IMPLODE($str));
}

function _VARS($vars) {
	return _P(_IMPLODE($vars));
}

function _VALS($vals) {
	return _P(_IMPLODE($vals));
}

function _COUNT($str = '*') {
	return sprintf('COUNT%s', _P($str));
}

function _DISTINCT($str) {
	return sprintf('DISTINCT%s', _P($str));
}

function heading($survey, $patient, $date, $sex = 'unknown') {
	return	'\documentclass[a4paper,twocolumn,swedish,11pt]{article}' . "\n" .
		'\usepackage[utf8]{inputenc}' . "\n" .
		'\usepackage[T1]{fontenc}' . "\n" .
		'\usepackage{babel}' . "\n" .
		'\usepackage{sectsty}' . "\n" .
		'\usepackage{url}' . "\n" .
		'\usepackage{marvosym}' . "\n" .
		'\allsectionsfont{\sffamily\bfseries}' . "\n" .
		'\addtolength{\oddsidemargin}{-1cm}' . "\n" .
		'\addtolength{\textwidth}{25mm}' . "\n" .
		'\pagestyle{empty}' . "\n" .
		'\begin{document}' . "\n" .
		'\section*{' . $survey . ', ' . $patient . '~' . $sex . ', ' . $date . '}' . "\n";
}

function texbox($str) {
	return '\fbox{\textbf{' . $str . '}}';
}

function list_atexts_with_boxed_answer(&$qa, $lead, &$atext, $answers, $question, $separator = ', ') {
	$str = '\item ' . $lead . "\n\n";
	$aarr = array();
	if (isset($qa[$question])) {
		foreach ($answers as $a) {
			if ($qa[$question] === $a) {
				$aarr[] = texbox($atext[$a]);
			} else {
				$aarr[] = $atext[$a];
			}
		}
		$str .= '{\footnotesize ' . implode($separator, $aarr) . '}' . "\n\n";
	} else {
		$str .= '---' . "\n\n";
	}
	return $str;
}

function print_answer_text(&$qa, $lead, &$AA, $question) { // AA is ATEXT or ASHORT
	$str  = '\item ' . $lead . "\n\n";
	if (isset($qa[$question])) {
		$str .= '{\footnotesize ' . $AA[$qa[$question]] . '}' . "\n\n";
	} else {
		$str .= '---' . "\n\n";
	}
	return $str;
}

function print_boxed_avalue(&$qa, $lead, &$AVALUE, $question) {
	$str  = '\item ' . $lead . ': ';
	if (isset($qa[$question])) {
		$str .= texbox($AVALUE[$qa[$question]]) . "\n\n";
	} else {
		$str .= '---' . "\n\n";
	}
	return $str;
}

function eq5d(&$qa) {
	$str  = '\item EQ5D: ';
	if (isset($qa['q3'], $qa['q4'], $qa['q5'], $qa['q6'], $qa['q7'])) {
		if ($qa['q3'] == 'a6' && $qa['q4'] == 'a9' && $qa['q5'] == 'a12' && $qa['q6'] == 'a15' && $qa['q7'] == 'a18') $eq5d = 1.00;
		else $eq5d = 1.00 - 0.081;
		if ($qa['q3'] == 'a7') $eq5d -= 0.069;
		if ($qa['q4'] == 'a10') $eq5d -= 0.104;
		if ($qa['q5'] == 'a13') $eq5d -= 0.036;
		if ($qa['q6'] == 'a16') $eq5d -= 0.123;
		if ($qa['q7'] == 'a19') $eq5d -= 0.071;
		if ($qa['q3'] == 'a8' || $qa['q4'] == 'a11' || $qa['q5'] == 'a14' || $qa['q6'] == 'a17' || $qa['q7'] == 'a20') $eq5d -= 0.269;
		if ($qa['q3'] == 'a8') $eq5d -= 0.314;
		if ($qa['q4'] == 'a11') $eq5d -= 0.214;
		if ($qa['q5'] == 'a14') $eq5d -= 0.094;
		if ($qa['q6'] == 'a17') $eq5d -= 0.386;
		if ($qa['q7'] == 'a20') $eq5d -= 0.236;
		$str .= '{\footnotesize' . texbox($eq5d) . '}' . "\n\n";
	} else {
		$str .= '{\footnotesize ofullständigt svar}' . "\n\n";
	}

	return $str;
}

function list_qtexts(&$qa, &$qtext, $lead, $questions, $answer, $separator) {
	$str = '\item ' . $lead . "\n\n";
	$qarr = array();
	foreach ($questions as $q) {
		if (isset($qa[$q]) && $qa[$q] === $answer && isset($qtext[$q])) {
			$qarr[] = $qtext[$q];
		}
	}
	if (!empty($qarr)) {
		$str .= '{\footnotesize ' . implode($separator, $qarr) . '}' . "\n\n";
	} else {
		$str .= '---' . "\n\n";
	}
	return $str;
}

function phys_activity_years(&$qa, &$QTEXT, &$AVALUE) {
	$str  = '\item Fysisk aktivitet olika åldrar' . "\n\n";
	$str .= '{\scriptsize\begin{enumerate}' . "\n";
	$str .= '\renewcommand{\theenumii}{\arabic{enumii}}' . "\n";
	$str .= '\renewcommand{\labelenumii}{\theenumii.}' . "\n";
	$str .= '\item Mestadels stillasittande, ibland någon promenad eller liknande' . "\n";
	$str .= '\item Lättare fysisk aktivitet, såsom gång, cykling eller trädgårdsarbete, minst 1--2~timmar i veckan' . "\n";
	$str .= '\item Mer ansträngande fysisk aktivitet, såsom motionslöpning, gympa eller bollsport, minst 1--2~timmar i veckan' . "\n";
	$str .= '\item Hård träning eller tävling, regelbundet och flera gånger i veckan' . "\n";
	$str .= '\end{enumerate}}' . "\n\n";

	$ans  = array(
		'a53' => '&' . $AVALUE['a53'] . '&&&',
		'a54' => '&&' . $AVALUE['a54'] . '&&',
		'a55' => '&&&' . $AVALUE['a55'] . '&',
		'a56' => '&&&&' . $AVALUE['a56']);

	$str .= '\begin{center}' . "\n";
	$str .= '{\footnotesize\begin{tabular}{r|cccc}' . "\n";
	$str .=	'&\textbf{1}&\textbf{2}&\textbf{3}&\textbf{4}\\\\\hline' . "\n";
	$aarr = array();
	foreach (array('q39', 'q40', 'q41', 'q42', 'q43', 'q44') as $q) {
		if (isset($qa[$q])) {
			$aarr[] = $QTEXT[$q] . $ans[$qa[$q]];
		}
	}
	$str .= implode('\\\\' . "\n", $aarr);
	$str .= "\n" . '\end{tabular}}' . "\n";
	$str .= '\end{center}' . "\n\n";
	return $str;
}

function ipaq(&$qa, &$QTEXT, &$ATEXT) {
	$str  = '\item Fysisk aktivitet, senaste 7 dagarna (IPAQ)' . "\n\n";

	$str .= '{\footnotesize\begin{itemize}' . "\n";
	foreach (array('q162' => 'q163', 'q164' => 'q165', 'q166' => 'q167') as $days => $time) {
		$str .= '\item ' . $QTEXT[$days] . ': ';
		if (isset($qa[$days])) {
			$str .= $ATEXT[$qa[$days]];
			if ($qa[$days] != 'a188' && isset($qa[$time])) {
				$str .= ', ' . $ATEXT[$qa[$time]];
			}
		} else {
			$str .= '---';
		}
		$str .= "\n";
	}
	$str .= '\item ' . $QTEXT['q168'] . ': ' . $ATEXT[$qa['q168']] . "\n";
	$str .= '\end{itemize}}' . "\n\n";
	return $str;
}

function dog(&$qa, &$ATEXT) {
	$str  = '\item Hund' . "\n\n";
	if (isset($qa['q49'])) {
		if ($qa['q49'] === 'a57') {
			$str .= '{\footnotesize Har hund';
			if (isset($qa['q50'])) {
				$str .= ', promenerar ' . $ATEXT[$qa['q50']] . ' i veckan';
			} else {
				$str .= ', inget svar om promenadtider';
			}
			$str .= '}' . "\n\n";
		} else {
			$str .= '{\footnotesize Har ingen hund}' . "\n\n";
		}
	} else {
		$str .= '---' . "\n\n";
	}
	return $str;
}

function foodhabits(&$qa) {
	$points = 0;
	foreach (array('q52', 'q53') as $q) {
		if (isset($qa[$q])) {
			switch ($qa[$q]) {
			case 'a58': $points += 1;	// sic. inga break!
			case 'a59': $points += 1;
			case 'a60': $points += 1;
			case 'a61':
			default:
			}
		}
	}
	switch ($qa['q54']) {
	case 'a62': $points += 1;		// sic. inga break!
	case 'a63': $points += 1;
	case 'a64': $points += 1;
	case 'a65':
	default:
	}
	switch ($qa['q55']) {
	case 'a61': $points += 1;
	case 'a60': $points += 1;
	case 'a67': $points += 1;
	case 'a66':
	default:
	}
	return '\item Matvanor: ' . texbox($points) . '~poäng' . "\n\n";
}

function tobacco(&$qa, &$ATEXT) {
	$str  = '\item Tobaksvanor' . "\n\n";
	if (isset($qa['q57']) || isset($qa['q59'])) {
		$str .= '{\footnotesize\begin{itemize}' . "\n";
		if (isset($qa['q57'])) {
			$str .= '\item ' . $ATEXT[$qa['q57']];
			if (isset($qa['q58'])) {
				$str .= ', ' . $ATEXT[$qa['q58']];
			}
			$str .= "\n";
		}
		if (isset($qa['q59'])) {
			$str .= '\item ' . $ATEXT[$qa['q59']];
			if (isset($qa['q60'])) {
				$str .= ', ' . $ATEXT[$qa['q60']];
			}
			$str .= "\n";
		}
		$str .= '\end{itemize}}' . "\n\n";
	} else {
		$str .= '---' . "\n\n";
	}
	return $str;
}

function alcohol(&$qa, &$ATEXT, $sex) {
	$str  = '\item Alkoholvanor' . "\n\n";

	if (isset($qa['q66'])) {
		$str .= '{\footnotesize Dricker ' . $ATEXT[$qa['q66']];
		if ($sex === 'male' && isset($qa['q67m̈́'])) {
			$str .= ', ' . $ATEXT[$qa['q67m']] . ' mer än fem standardglas vid ett och samma tillfälle.';
		} else if ($sex === 'female' && isset($qa['q67f'])) {
			$str .= ', ' . $ATEXT[$qa['q67f']] . ' mer än fyra standardglas vid ett och samma tillfälle.';
		}
		$str .= '}';
	} else {
		$str .= '---';
	}
	$str .= "\n\n";
	return $str;
}

function common_situations(&$qa) {
	$str  = '\item Vardagliga situationer' . "\n\n";

	$asiq = array('q69', 'q70', 'q71', 'q72', 'q73', 'q74', 'q75', 'q76', 'q77', 'q78', 'q79', 'q80', 'q81', 'q82', 'q83', 'q84');
	$sefq = array('q86', 'q87', 'q88', 'q89', 'q90', 'q91', 'q92', 'q93', 'q94', 'q95');

	$asi = 0;
	foreach ($asiq as $q) {
		if (isset($qa[$q])) {
			switch ($qa[$q]) {
			case 'a98': $asi += 1;
			case 'a97': $asi += 1;
			case 'a96': $asi += 1;
			case 'a95': $asi += 1;
			case 'a94':
			default:
			}
		}
	}

	$sef = 0;
	foreach ($sefq as $q) {
		if (isset($qa[$q])) {
			switch ($qa[$q]) {
			case 'a102': $sef += 1;
			case 'a101': $sef += 1;
			case 'a100': $sef += 1;
			case 'a99':  $sef += 1;
			default:
			}
		}
	}

	$str .= '{\footnotesize\begin{itemize}' . "\n";
	$str .= '\item ASI-poäng: ' . texbox($asi) . "\n";
	$str .= '\item Self efficacy-poäng: ' . texbox($sef) . "\n";
	$str .= '\end{itemize}}' . "\n\n";
	return $str;
}

function anxiety(&$qa) {
	$str  = '\item Ångest/oro: {\footnotesize ';

	$aq = array(
		'q97'	=> array('a103' => 3, 'a104' => 2, 'a105' => 1, 'a106' => 0),
		'q99'	=> array('a110' => 3, 'a111' => 2, 'a112' => 1, 'a106' => 0),
		'q101'	=> array('a103' => 3, 'a116' => 2, 'a112' => 1, 'a106' => 0),
		'q103'	=> array('a120' => 0, 'a121' => 1, 'a118' => 2, 'a89'  => 3),
		'q105'	=> array('a89'  => 0, 'a119' => 1, 'a116' => 2, 'a123' => 3),
		'q107'	=> array('a123' => 3, 'a116' => 2, 'a118' => 1, 'a106' => 0),
		'q109'	=> array('a123' => 3, 'a116' => 2, 'a118' => 1, 'a89'  => 0));
	$oq = array(
		'q98'	=> array('a107' => 0, 'a108' => 1, 'a109' => 2, 'a94'  => 3),
		'q100'	=> array('a113' => 0, 'a114' => 1, 'a115' => 2, 'a89'  => 3),
		'q102'	=> array('a89'  => 3, 'a118' => 2, 'a119' => 1, 'a103' => 0),
		'q104'	=> array('a122' => 3, 'a104' => 2, 'a119' => 1, 'a89'  => 0),
		'q106'	=> array('a124' => 3, 'a125' => 2, 'a126' => 1, 'a106' => 0),
		'q108'	=> array('a127' => 0, 'a128' => 1, 'a129' => 2, 'a130' => 3),
		'q110'	=> array('a104' => 0, 'a119' => 1, 'a118' => 2, 'a131' => 3));

	$a = 0;
	foreach ($aq as $q => $ans) {
		if (isset($qa[$q])) {
			foreach ($ans as $answer => $p) {
				if ($qa[$q] != $answer) continue;
				$a += $p;
				//echo '$q = ' . $q . ', $answer = ' . $qa[$q] . ', adding ' . $p . ', $a = ' . $a . "\n";
			}
		}
	}
	$str .= texbox($a) . '~/~';

	$o = 0;
	foreach ($oq as $q => $ans) {
		if (isset($qa[$q])) {
			foreach ($ans as $answer => $p) {
				if ($qa[$q] != $answer) continue;
				$o += $p;
				//echo '$q = ' . $q . ', $answer = ' . $qa[$q] . ', adding ' . $p . ', $o = ' . $o . "\n";
			}
		}
	}
	$str .= texbox($o) . '}' . "\n\n";
	return $str;
}

function motivation(&$qa) {
	$str  = '\item Om fysisk aktivitet och motion' . "\n\n";

	$str .= '{\footnotesize\begin{enumerate}' . "\n";

	$inner_q = array('q112', 'q114', 'q117', 'q119', 'q122', 'q124');
	$outer_q = array('q113', 'q115', 'q118', 'q120', 'q123', 'q125');
	$amoti_q = array('q116', 'q121', 'q126');
	$ability = array('q128', 'q129', 'q130', 'q131');
	$support = array('q133', 'q134', 'q135', 'q136', 'q137', 'q138');

	$questions = array(
		'inre motivation' => $inner_q,
		'yttre motivation' => $outer_q,
		'amotivation' => $amoti_q,
		'förmåga' => $ability,
		'stöd' => $support);

	$answers = array('a1321' => 1, 'a1322' => 2, 'a1323' => 3, 'a1334' => 4, 'a1335' => 5, 'a1336' => 6, 'a1347' => 7);

	foreach ($questions as $group => $detailed_questions) {
		$str .= '\item ' . $group . ': ';
		$num = 0;
		$sum = 0.0;
		foreach ($detailed_questions as $q) {
			if (isset($qa[$q])) {
				switch ($qa[$q]) {
				case 'a1321':
				case 'a1322':
				case 'a1323':
				case 'a1334':
				case 'a1335':
				case 'a1336':
				case 'a1347': $sum += $answers[$qa[$q]]; $num += 1.0;
				default: /* ogiltigt svar */
				}
			}
		}
		if ($num > 0) {
			$str .= $sum . '/' . $num . ' = ' . texbox(sprintf("%2.2f", $sum/$num)) . "\n";
		} else {
			$str .= '---' . "\n";
		}
	}
	$str .= '\end{enumerate}}' . "\n\n";
	return $str;
}

function footing($stoptags) {
	$str = '';
	foreach ($stoptags as $st) {
		$str .= '\end{' . $st . '}' . "\n";
	}
	return $str;
}

//================== MAIN ===============================================================================================

$quests =
	_SELECT(array(
		'tag',
		'text')
	)							.
	_FROM('questions');
$qres = array();
$QTEXT = array();
if (_PGSQL($quests, $qres) > 0) foreach ($qres as $row) {
	$QTEXT[$row['tag']] = $row['text'];
}

$answs =
	_SELECT(array(
		'tag',
		'text',
		'short',
		'value')
	)							.
	_FROM('answers');
$ares = array();
$ATEXT = array();
$ASHORT = array();
$AVALUE = array();
if (_PGSQL($answs, $ares) > 0) foreach ($ares as $row) {
	$ATEXT[$row['tag']] = $row['text'];
	$ASHORT[$row['tag']] = $row['short'];
	$AVALUE[$row['tag']] = $row['value'];
}

$dalby1_lists =
	_SELECT('lists.id AS list')				.
	_FROM('lists')						.
	_JOIN('idops', 'idops.id = lists.idop')			.
	_JOIN('vcs', 'vcs.id = idops.vc');

$dalby1_patients =
	_SELECT('patients.token AS patient')			.
	_FROM('patients')					.
	_WHERE('list IN ' . _P($dalby1_lists));

$dalby1_responses =
	_SELECT(array(
		'responses.id AS response',
		'responses.stamp AS stamp',
		'responses.patient AS patient',
		'surveys.id AS survey')
	)							.
	_FROM('responses')					.
	_JOIN('surveys', 'surveys.id = responses.survey')	.
	_WHERE(_AND(array(
		'patient IN ' . _P($dalby1_patients),
		"surveys.name = 'Dalby 1'",
		'tex IS NULL'))
	)							.
	_ORDERBY('responses.stamp');

$responses = array();
$q = _PGSQL($dalby1_responses, $responses);
if ($q === 0) {
	echo 'Dalby 1: nothing to do.' . "\n";
} else foreach ($responses as $r) {
	$RESPONSE_ID = $r['response'];

	$questions_answers =
		_SELECT(array(
			'questions.tag AS q',
			'answers.tag AS a')
		)								.
		_FROM('rdet')							.
		_JOIN('questions_answers', _AND(array(
			'rdet.question = questions_answers.question',
			'rdet.answer = questions_answers.answer'))
		)								.
		_JOIN('questions', 'questions.id = questions_answers.question')	.
		_JOIN('answers', 'answers.id = questions_answers.answer')	.
		_WHERE('rdet.response = ' . $RESPONSE_ID);

	$qa = array();
	$q = _PGSQL($questions_answers, $qa);
	if ($qa == 0) {
		continue;
	}
	/* else */
	$QA = array();
	foreach ($qa as $row) {
		$QA[$row['q']] = $row['a'];
	}

	//var_dump($QA); die();

	$SEX = 'unknown';
	if (isset($QA['q0000'])) {
		if ($QA['q0000'] === 'a0001') {
			$SEX = '\Male';
		} else {
			$SEX = '\Female';
		}
	}

	$tex  = heading('Dalby~1', $r['patient'], date('Y-m-d', strtotime($r['stamp'])), $SEX);

	$tex .= '\begin{enumerate}' . "\n";

	// Allmänt välbefinnande
	$answers = array('A1', 'A2', 'A3', 'A4', 'A5');
	$tex .= list_atexts_with_boxed_answer($QA, 'Allmänt välbefinnande', $ATEXT, $answers, 'q1');

	// EQ5D
	$tex .= eq5d($QA);

	// Läkemedel/preparat
	$questions = array('q9', 'q10', 'q11', 'q12', 'q13', 'q14', 'q15', 'q16', 'q17', 'q18', 'q19', 'q20', 'q21', 'q23', 'q24');
	$substance_answers = array('a22' /* 3 månader */, 'a23' /* 2 veckor */);
	foreach ($substance_answers as $answer) {
		$tex .= list_qtexts($QA, $QTEXT, $ATEXT[$answer], $questions, $answer, ', ');
	}

	// Besvär/symtom
	$questions = array('q26', 'q27', 'q28', 'q29', 'q30');
	$issue_answers = array('a24' /* svåra */, 'a25' /* lätta */);
	foreach ($issue_answers as $answer) {
		$tex .= list_qtexts($QA, $QTEXT, $ATEXT[$answer], $questions, $answer, '; ');
	}

	// Stressad
	$answers = array('a26', 'a27', 'a28');
	$tex .= list_atexts_with_boxed_answer($QA, 'Stressad i vardagen', $ATEXT, $answers, 'q31');

	// Sömn
	$answers = array('a29', 'a30', 'a31', 'a32', 'a33');
	$tex .= list_atexts_with_boxed_answer($QA, 'Sömn på det hela taget', $ATEXT, $answers, 'q32');

	// Göra själv
	$tex .= print_answer_text($QA, 'Göra själv för att bevara en god hälsa', $ATEXT, 'q33');

	// Förändringsbenägenhet
	$tex .= print_answer_text($QA, 'Stages of change -- förändringsbenägenhet', $ASHORT, 'q34');

	// Fysisk träning
	$tex .= print_boxed_avalue($QA, 'Fysisk träning (min/vecka)', $AVALUE, 'q35');

	// Vardagsmotion
	$tex .= print_boxed_avalue($QA, 'Vardagsmotion (min/vecka)', $AVALUE, 'q36');

	// Fysisk aktivitet under åren
	$tex .= phys_activity_years($QA, $QTEXT, $AVALUE);

	// Fysisk aktivitet, senaste 7 dagarna (IPAQ)
	$tex .= ipaq($QA, $QTEXT, $ATEXT);

	// Hund
	$tex .= dog($QA, $ATEXT);

	// Matvanor
	$tex .= foodhabits($QA);

	// Tobak
	$tex .= tobacco($QA, $ATEXT);

	// Alkohol
	$tex .= alcohol($QA, $ATEXT, $SEX);

	// Vardagliga situationer
	$tex .= common_situations($QA);

	// Ångest/oro
	$tex .= anxiety($QA);

	// Om fysisk aktivitet och motion
	$tex .= motivation($QA);

	$tex .= footing(array('enumerate', 'document'));

	//echo $tex;
	$result = array();
	if (_PGSQL(_UPDATE('responses', array('tex' => str_replace('\\', '\\\\', $tex)), 'id = ' . $RESPONSE_ID), $result) > 0) {
		echo 'Successfully converted ' . $r['patient'] . "\n";
	} else {
		echo 'Error updating response for ' . $r['patient'] . "\n";
	}
}

//=================================================================================================================================
// CASE REPORT FORMS
//=================================================================================================================================

?>
