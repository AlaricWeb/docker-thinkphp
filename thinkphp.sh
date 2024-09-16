#!/bin/sh
set -e

php think    worker:gateway -d


exec php-fpm 