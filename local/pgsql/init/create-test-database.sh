#!/bin/bash

set -e
set -u

if [ -n "$TEST_DATABASE" ]; then
	echo "Creating test database '$TEST_DATABASE' and granting privileges to user '$POSTGRES_USER'"
    psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" <<- EOSQL
        CREATE DATABASE $TEST_DATABASE;
        GRANT ALL PRIVILEGES ON DATABASE $TEST_DATABASE TO $POSTGRES_USER;
EOSQL
fi
