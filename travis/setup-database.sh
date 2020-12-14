#!/bin/bash
#
# This file is part of the phpBB Forum Software package.
#
# @copyright (c) phpBB Limited <https://www.phpbb.com>
# @license GNU General Public License, version 2 (GPL-2.0)
#
# For full copyright and license information, please see
# the docs/CREDITS.txt file.
#
set -e
set -x

DB=$1
TRAVIS_PHP_VERSION=$2
NOTESTS=$3
MYISAM=$4

if [ "$NOTESTS" == '1' ]
then
	exit 0
fi

if [ "$DB" == "postgres" ]
then
	psql -c 'DROP DATABASE IF EXISTS phpbb_tests;' -U postgres
	psql -c 'create database phpbb_tests;' -U postgres
fi

if [ "$DB" == "mssql" ]
then
	sudo su -c "curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add -"
	sudo su -c "curl https://packages.microsoft.com/config/ubuntu/18.04/prod.list > /etc/apt/sources.list.d/mssql-release.list"
	sudo apt-get update
	sudo ACCEPT_EULA=Y apt-get install msodbcsql17
	sudo ACCEPT_EULA=Y apt-get install mssql-tools
	echo 'export PATH="$PATH:/opt/mssql-tools/bin"' >> ~/.bash_profile
	echo 'export PATH="$PATH:/opt/mssql-tools/bin"' >> ~/.bashrc
	sudo apt install php$TRAVIS_PHP_VERSION-dev unixodbc-dev
	sudo pecl config-set php_ini /etc/php/$TRAVIS_PHP_VERSION/fpm/php.ini
	sudo pecl -d php_suffix=7.2 install sqlsrv-5.3.0
	sudo pecl -d php_suffix=7.2 install pdo_sqlsrv-5.3.0
	sudo su -c "printf \"; priority=20\nextension=sqlsrv.so\n\" > /etc/php/$TRAVIS_PHP_VERSION/mods-available/sqlsrv.ini"
	sudo su -c "printf \"; priority=30\nextension=pdo_sqlsrv.so\n\" > /etc/php/$TRAVIS_PHP_VERSION/mods-available/pdo_sqlsrv.ini"
	sudo phpenmod -v $TRAVIS_PHP_VERSION sqlsrv pdo_sqlsrv
	sudo service php$TRAVIS_PHP_VERSION-fpm restart
fi

if [ "$MYISAM" == '1' ]
then
	mysql -h 127.0.0.1 -u root -e 'SET GLOBAL storage_engine=MyISAM;'
fi
