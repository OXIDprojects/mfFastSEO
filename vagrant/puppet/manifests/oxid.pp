exec { "OXID download":
    command => "wget -O /tmp/oxid.zip http://wiki.oxidforge.org/download/OXID_ESHOP_CE_4.8.3.zip",
    unless => "test -f /tmp/oxid.zip",
    user => "vagrant",
    group => "www-data",
    timeout => 0,
}

file {"/srv/mfFastSEO.local/":
    owner => "vagrant",
    group => "www-data",
    mode => "0775",
    ensure => directory,
}

exec {"OXID unzip":
    command => "unzip -d /srv/mfFastSEO.local/ /tmp/oxid.zip",
    onlyif => "test -f /tmp/oxid.zip",
    unless => "test -e /srv/mfFastSEO.local/index.php",
    user => "vagrant",
    group => "www-data",
    require => [Exec["OXID download"], File["/srv/mfFastSEO.local/"]],
}

exec {"create OXID-DB":
    command => "mysql -uroot -sNe 'CREATE DATABASE `shop` default charset utf8 collate utf8_general_ci'",
    onlyif => "test `mysql -uroot -sNe \"SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'shop'\"` -eq 0",
    require => [Package["mysql-server"], Package["mysql-client"]],
}

exec {"create OXID-DB user":
    command => "mysql -uroot -sNe 'GRANT ALL PRIVILEGES ON `shop`.* TO shop@localhost'",
    require => [Package["mysql-server"], Package["mysql-client"]],
}

file {"/etc/apache2/sites-available/mfFastSEO.local":
    source => "/vagrant/files/etc/apache2/sites-available/mfFastSEO.local",
    require => Package["apache2"],
    owner => "root",
    group => "root",
}

exec {"a2ensite mfFastSEO.local":
    require => [Package["apache2"], File["/etc/apache2/sites-available/mfFastSEO.local"]],
    notify => Service["apache2"],
}

file {"/etc/apache2/envvars":
    source => "/vagrant/files/etc/apache2/envvars",
    require => Package["apache2"],
    notify => Service["apache2"],
    owner => "root",
    group => "root",
}

file {"/var/lock/apache2":
    owner => "vagrant",
    group => "root",
    recurse => true,
    require => Package["apache2"],
    notify => Service["apache2"],
}

host {"mfFastSEO.local":
    ip => "127.0.0.1",
}

