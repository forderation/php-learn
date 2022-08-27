#!/bin/bash

psql -d api_php_learn -U postgres -a -f migration.sql