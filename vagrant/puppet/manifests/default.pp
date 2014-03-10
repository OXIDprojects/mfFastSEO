Exec { path => [ '/bin/', '/sbin/', '/usr/bin/', '/usr/sbin/' ] }

import "apache.pp"
import "php.pp"
import "mysql.pp"
import "oxid.pp"