#!/usr/bin/bash

SIZE=500M
DIR=~/handin

for F in $(find $DIR -type f -size +$SIZE)
do
  echo "removing `ls $F -la`"  
  #DANGEROUS, only uncomment if SURE about parameters
  rm -f $F
done
