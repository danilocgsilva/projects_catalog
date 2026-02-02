#!/bin/bash

set -e

sudo chown -Rv $USER:$USER app/
if [ -f app/vendor ]
then
	rm -rv app/vendor
else
	echo "app/vendor does not exists"
fi

if [ -f app/.env ]
then
	rm app/.env
else
	echo "app/.env does not exists"
fi

