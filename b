#!/usr/bin/env bash
rm -rf public && npx antora --fetch local-playbook.yml
cp css/*.css public/_/css/
find public/genealogy/1.0 -name which-anne-volkening.html -exec sed -i "s/[^/][a-z]\+\.css/wide-site\.css/" {} \;
find public/genealogy/1.0 -name obituar*.html -exec sed -i "s/[^/][a-z]\+\.css/wide-site\.css/" {} \;
find public/genealogy/1.0 -name timeline.html -exec sed -i "s/[^/][a-z]\+\.css/wide-timeline\.css/" {} \;
find public/genealogy/1.0/petzen -name *.html -exec sed -i "s/[^/][a-z]\+\.css/wide-site\.css/" {} \;
find public/genealogy/1.0/frille -name *.html -exec sed -i "s/[^/][a-z]\+\.css/wide-site\.css/" {} \;
