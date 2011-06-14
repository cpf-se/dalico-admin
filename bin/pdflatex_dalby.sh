#!/bin/sh

. ./psql.sh

temp=`mktemp`
echo "SELECT patient, to_char(stamp, 'YYYY-MM-DD') FROM responses WHERE tex IS NOT NULL AND pdf_url IS NULL" | $PSQL | tr '|' '\t' > $temp
while read pat dat; do
	echo -n "Dalby1 $pat... "
	tf=`mktemp`
	echo "SELECT tex FROM responses WHERE patient = '$pat'" | $PSQL > $tf
	fn="$pat"_dalby1_"$dat"
	mv $tf ./$fn.tex
	pdflatex $fn 2>&1 >/dev/null
	echo "UPDATE responses SET pdf_url = '/pdf/$fn.pdf' WHERE patient = '$pat'" | $PSQL
	rm -f *.aux *.log
done < $temp

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

