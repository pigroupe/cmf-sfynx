#!/bin/bash
set -e

if [ "$1" = 'deploy' ]; then
    chown -R deploy "$HOME"

    exec gosu deploy "$@"
fi

exec "$@"