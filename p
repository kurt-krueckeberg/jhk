#!/usr/bin/env bash
rm -rf public
npx antora local-playbook.yml 
cp  css/kurt-customizations.css public/_/css/
cp  fonts/* public/_/font/
