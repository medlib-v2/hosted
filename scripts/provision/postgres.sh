#!/usr/bin/env bash

if [ -n "$1" ]; then
	if ! su postgres -c "psql $1 -c '\q' 2>/dev/null"; then
    	su postgres -c "createdb -O hosted '$1'"
	fi
fi