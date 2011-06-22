#!/bin/sh

. ./psql.sh

temp=`mktemp`
echo "SELECT patient, stamp, to_char(stamp, 'YYYY-MM-DD') AS date, survey, surveys.name FROM responses JOIN surveys ON surveys.id = responses.survey WHERE tex IS NOT NULL AND pdf_url IS NULL" | $PSQL > $temp
while IFS='|' read pat sta dat sur nam; do
	echo -n "$pat ( $sur, $dat ) "
	tf=`mktemp`
	echo "SELECT tex FROM responses WHERE patient = '$pat' AND survey = $sur AND stamp = '$sta'" | $PSQL > $tf
	fn="$pat"_`echo $nam | sed 's, ,,' | tr 'D' 'd'`_"$dat"
	mv $tf ./$fn.tex
	pdflatex $fn 2>&1 >/dev/null
	echo "UPDATE responses SET pdf_url = '/pdf/$fn.pdf' WHERE patient = '$pat' AND survey = $sur AND stamp = '$sta'" | $PSQL
	rm -f *.aux *.log
done < $temp

rm -f $temp
exit 0

echo "SELECT patient, date FROM crfs WHERE tex IS NOT NULL AND pdf_url IS NULL" | $PSQL | tr '|' '\t' > $temp
while read pat dat; do
	echo -n "CRF $pat... "
	tf=`mktemp`
	echo "SELECT tex FROM crfs WHERE patient = '$pat'" | $PSQL > $tf
	fn="$pat"_crf_"$dat"
	mv $tf ./$fn.tex
	pdflatex $fn 2>&1 >/dev/null
	echo "UPDATE crfs SET pdf_url = '/pdf/$fn.pdf' WHERE patient = '$pat' AND date = '$dat'" | $PSQL
	rm -f *.aux *.log
done < $temp

echo "SELECT patient, date FROM ivps WHERE tex IS NOT NULL AND pdf_url IS NULL" | $PSQL | tr '|' '\t' > $temp
while read pat dat; do
	echo -n "IVP $pat... "
	tf=`mktemp`
	echo "SELECT tex FROM ivps WHERE patient = '$pat'" | $PSQL > $tf
	fn="$pat"_ivp_"$dat"
	mv $tf ./$fn.tex
	pdflatex $fn 2>&1 >/dev/null
	echo "UPDATE ivps SET pdf_url = '/pdf/$fn.pdf' WHERE patient = '$pat' AND date = '$dat'" | $PSQL
	rm -f *.aux *.log
done < $temp

rm -f $temp

