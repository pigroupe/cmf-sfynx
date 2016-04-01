#!/bin/bash
nom_bdd=$1

echo "\n\n###################################"
echo "######    DROITS $nom_bdd    ######"
echo "###################################"

#creation du sql
psql $nom_bdd -U postgres -A -t -f crea_droits.sql -o droits_$nom_bdd.sql

#passage du sql
psql $nom_bdd -U postgres  -f droits_$nom_bdd.sql

#suppression du sql
rm droits_$nom_bdd.sql
