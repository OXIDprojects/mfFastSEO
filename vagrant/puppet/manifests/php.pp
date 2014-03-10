package { "php5":
    require => Package["apache2"],
    notify => Service["apache2"]
}
package { "php5-cli":
    require => Package["php5"],
    notify => Service["apache2"]
}
package { "php5-common":
    require => Package["php5"],
    notify => Service["apache2"]
}
package { "php5-curl":
    require => Package["php5"],
    notify => Service["apache2"]
}
package { "php5-mcrypt":
    require => Package["php5"],
    notify => Service["apache2"]
}
package { "php5-mysqlnd":
    require => Package["php5"],
    notify => Service["apache2"]
}
package { "php5-recode":
    require => Package["php5"],
    notify => Service["apache2"]
}
package { "php5-xdebug":
    require => Package["php5"],
    notify => Service["apache2"]
}
package { "php5-gd":
    require => Package["php5"],
    notify => Service["apache2"]
}
package { "php-apc":
    require => Package["php5"],
    notify => Service["apache2"]
}