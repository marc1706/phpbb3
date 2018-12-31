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

# MariaDB Series
VERSION='5.5'

# Operating system codename, e.g. "precise"
OS_CODENAME=$(lsb_release --codename --short)

# Manually purge MySQL to remove conflicting files (e.g. /etc/mysql/my.cnf)
#sudo apt-get purge -y mysql* mariadb*

if ! which add-apt-repository > /dev/null
then
	sudo apt-get update
	sudo apt-get install -y python-software-properties
fi

#sudo apt-get update
#sudo find /var/lib/mysql -name "debian-*.flag" -exec rm {} \;

#sudo debconf-set-selections <<< "mariadb-server-$VERSION mysql-server/root_password password rootpasswd"
#sudo debconf-set-selections <<< "mariadb-server-$VERSION mysql-server/root_password_again password rootpasswd"
#sudo apt-get install -y mariadb-server

# Set root password to empty string.
#echo "
#USE mysql;
#UPDATE user SET Password = PASSWORD('') where User = 'root';
#FLUSH PRIVILEGES;
#" | mysql -u root -prootpasswd

mysql --version
