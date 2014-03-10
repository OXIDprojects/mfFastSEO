#!/bin/sh
apt-get -y -qq update >/dev/null || exit $?
apt-get -y -qq install python-software-properties >/dev/null || exit $?
apt-get -y -qq install build-essential vim vim-common bash-completion unzip >/dev/null || exit $?
update-alternatives --quiet --set editor /usr/bin/vim.basic >/dev/null || exit $?

cp /vagrant/files/etc/profile.d/bash_aliases.sh /etc/profile.d/bash_aliases.sh
