#!/usr/bin/env bash

if [ -n "$1" ] && [ -n "$2" ]; then
	SSL_CONFIG="/etc/nginx/ssl/${1}.cnf"
	SSL_KEY="/etc/nginx/ssl/${1}.key"
	SSL_CERT="/etc/nginx/ssl/${1}.crt"

	if [ ! -f ${SSL_CONFIG} ] || [ ! -f ${SSL_KEY} ] || [ ! -f ${SSL_CERT} ]; then
		echo "$2" | tee "${SSL_CONFIG}" > /dev/null 2>&1

		export TPL_SITE="$1"
		export TPL_SSL_KEY="$SSL_KEY"

		go-replace --mode=template "${SSL_CONFIG}"

		openssl genrsa -des3 -out "${SSL_KEY}" 4096 2>/dev/null
		openssl req -new -x509 -config "${SSL_CONFIG}" -out "${SSL_CERT}" -days 365 2>/dev/null
	fi
fi
