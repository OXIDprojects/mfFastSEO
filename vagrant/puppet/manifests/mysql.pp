package { "mysql-client": }
package { "mysql-server": }
package { "mysql-common": }

service {"mysql":
    ensure => running,
    hasrestart => true,
    hasstatus => true,
    require => Package["mysql-server"]
}