<?php
namespace core\lib\db;

use \core\lib\db\SQL;

class DB extends \core\lib\db\MySQL
{
    private $sql = null;
    public function __construct($table)
    {
        $this->sql = new SQL($table);
        parent::__construct();
    }

    //返回插入的id
    public function insert()
    {

        //_e($this->sql->insert(func_get_args()));
        return $this->pdoExec($this->sql->insert(func_get_args()));

    }
    public function inserts()
    {

        $args = func_get_args();
        $param = array_shift($args);
        // _e($this->sql->inserts($param));
        return $this->pdoExec($this->sql->inserts($param), [$param, $args]);

    }
    public function select()
    {
        //echo $this->sql->select(func_get_args());
        return $this->pdoSelect($this->sql->select(func_get_args()));

    }
    //返回影响的行数
    public function delete()
    {
        return $this->pdoExec($this->sql->delete());

    }
    public function where()
    {
        $this->sql->where(func_get_args());
        return $this;
    }
    function  and () {
        $this->sql->where(func_get_args(), " AND ");
        return $this;
    }
    function  or () {
        $this->sql->where(func_get_args(), " OR ");
        return $this;
    }
    public function updata()
    {
        // echo $this->sql->updata(func_get_args());
        return $this->pdoExec($this->sql->updata(func_get_args()));
    }

    public function order()
    {

        $this->sql->order(func_get_args());
        return $this;
    }
}