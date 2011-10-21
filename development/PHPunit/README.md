Smarty PHP Unit Tests
=====================

Prerequisites
-------------

These instructions assume you are on a unix compatible OS. For Windows, please make appropriate adjustments for your OS.

To run Smarty's PHP unit tests, you need to have installed on your system:

* PEAR  [install](http://pear.php.net/manual/en/installation.php)
* phpunit [install](http://www.phpunit.de/manual/3.0/en/installation.html)
* MySQL [install](http://dev.mysql.com/doc/refman/5.5/en/installing.html)

Make sure MySQL is listening on localhost, which should be default.
You need to setup some information in MySQL:

```sql
CREATE TABLE IF NOT EXISTS `templates` (
 `name` varchar(100) NOT NULL,
 `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 `source` text,
 PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `templates` (`name`, `modified`, `source`) VALUES ('test.tpl', "2010-12-25 22:00:00", '{$x="hello world"}{$x}');
CREATE TABLE IF NOT EXISTS `output_cache` (
 `id` CHAR(40) NOT NULL COMMENT 'sha1 hash',
 `name` VARCHAR(250) NOT NULL,
 `cache_id` VARCHAR(250) NULL DEFAULT NULL,
 `compile_id` VARCHAR(250) NULL DEFAULT NULL,
 `modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `content` LONGTEXT NOT NULL,
 PRIMARY KEY (`id`),
 INDEX(`name`),
 INDEX(`cache_id`),
 INDEX(`compile_id`),
 INDEX(`modified`)
) ENGINE = InnoDB;
GRANT ALL ON test.* TO smarty@"localhost" IDENTIFIED BY 'smarty';
```

Make sure you have write permission to templates, templates_c and cache dirs inside the PHPUnit directory.

Running Tests
-------------

* change working directory to the developer/PHPunit directory.
* copy phpunit-test.sh-dist to phpunit-test.sh
* edit phpunit-test.sh file and adjust paths to php and phpunit if necessary
* set execute perms on phpunit-test.sh if necessary
* execute `./phpunit-tests.sh`

You should get the output of the unit tests to stdout.
