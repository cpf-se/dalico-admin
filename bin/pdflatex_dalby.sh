#!/bin/sh

PSQL='psql -hlocalhost -p54321 -Udalico -ddalico -Atw'

PATS="SELECT patient,to_char(stamp,'YYYY-MM-DD') FROM responses WHERE tex IS NOT NULL"

pats_dates() {
	echo $PATS | $PSQL | tr '|' '\t'
}

gettex() {
	echo "SELECT tex FROM responses WHERE patient = '$1'" | $PSQL
}

instodb() {
	echo "UPDATE responses SET pdf_url = '$1' WHERE patient = '$2'" | $PSQL
}

pats_dates | while read pat dat
	do
		tf=`mktemp`
		gettex $pat > $tf
		filename="$pat"_dalby1_"$dat"
		mv $tf ./$filename.tex
		pdflatex $filename 2>&1 >/dev/null
		instodb "/pdf/$filename.pdf" $pat
		rm -f $tf $tf.* *.aux *.log
	done

