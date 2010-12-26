<?php

/*
    -- considering the following mysql schema
    CREATE TABLE IF NOT EXISTS `templates` (
      `name` varchar(100) NOT NULL,
      `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      `source` text,
      PRIMARY KEY (`name`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    INSERT INTO `templates` (`name`, `modified`, `source`) VALUES ('test.tpl', "2010-12-25 22:00:00", '{$x="hello world"}{$x}');
*/

class Smarty_Resource_Mysqls extends Smarty_Resource_Custom {
    // PDO instance
    protected $db;
    // prepared fetch() statement
    protected $fetch;

    public function __construct() {
        try {
            $this->db = new PDO("mysql:dbname=test;host=127.0.0.1", "smarty", "smarty");
        } catch (PDOException $e) {
            throw new SmartyException('Mysql Resource failed: ' . $e->getMessage());
        }
        $this->fetch = $this->db->prepare('SELECT modified, source FROM templates WHERE name = :name');
    }
    
    /**
     * Fetch a template and its modification time from database
     *
     * @param string $name template name
     * @param string $source template source
     * @param integer $mtime template modification timestamp (epoch)
     * @return void
     */
    protected function fetch($name, &$source, &$mtime)
    {
        $this->fetch->execute(array('name' => $name));
        $row = $this->fetch->fetch();
        $this->fetch->closeCursor();
        if ($row) {
            $source = $row['source'];
            $mtime = strtotime($row['modified']);
        } else {
            $source = null;
            $mtime = null;
        }
    }
  
    // NOTE: this is required for PHPUnit, it seems to serialize things for some reason    
    public function __sleep()
    {
        return array();
    }
    
    // NOTE: this is required for PHPUnit, it seems to serialize things for some reason
    public function __wakeup()
    {
        $this->__construct();
    }
}