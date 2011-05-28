#!/bin/sh

echo "drop database if exists dalico ; create database dalico owner dalico template template0 encoding 'UTF8' ;" | psql template1
rm -f .*.done

