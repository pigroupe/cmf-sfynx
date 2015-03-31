#!/bin/sh

#Vous devez disposer de nodejs et de npm.

# Deps
npm install -g grunt grunt-init yo
npm install grunt-cli generator-bootstrap-less
npm install grunt-uncss  grunt-shell grunt-remove-logging grunt-spritesmith grunt-newer grunt-lazyload --save
npm install grunt-pagespeed grunt-yslow grunt-notify grunt-browser-sync --save-dev

# Bootstraping project
yo bootstrap-less

