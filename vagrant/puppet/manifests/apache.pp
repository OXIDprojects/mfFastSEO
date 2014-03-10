package { "apache2": }

exec { "a2enmod rewrite":
    require => Package["apache2"],
    notify => Service["apache2"]
}

service { "apache2":
    ensure     => running,
    enable     => true,
    hasrestart => true,
    hasstatus  => true,
    require    => Package["apache2"]
}
