<?php
namespace core\lib\db;

class SQL
{
    private $select = "SELECT ";
    private $insert = "INSERT INTO ";
    private $delete = "DELETE FROM ";
    private $updata = "UPDATE ";
    private $value = " VALUES ";
    private $set = " SET ";
    private $from = " FROM ";
    private $space = " ";

    private $key = "*";
    private $where = "";

    private $table = "";

    public function __construct($table)
    {
        $this->table = $table;
    }

    /**
     * 查询
     */
    public function select()
    {

        if (func_get_args() != [[]]) {
            $this->key = implode(array_reduce(func_get_args(), 'array_merge', array()), ",");
        }
        return $this->select . $this->key . $this->from . $this->table . $this->where;
    }
    /**
     * 插入
     */
    public function insert($data)
    {
        if ($data != []) {
            foreach ($data as $index) {
                $keyArr = [];
                $valueArr = [];
                foreach ($index as $key => $value) {
                    $keyArr[] = $key;
                    $valueArr[] = markString($value);
                }
                $sql[] = $this->insert . $this->table . " (" . implode($keyArr, ",") . ")" . $this->value . "(" . implode($valueArr, ",") . ");   ";
            }
            return $sql;
        } else {
            error("无插入数据");
        }

    }
    /**
     * 批量插入
     */
    public function inserts($data)
    {

        if (is_array($data)) {
            foreach ($data as $key) {
                $keyArr[] = $key;
                $valueArr[] = ":" . $key;
            }
            return $this->insert . $this->table . " (" . implode($keyArr, ",") . ")" . $this->value . "(" . implode($valueArr, ",") . ") ";
        } else {
            error("无插入数据");
        }

    }
    /**
     * 更新
     */
    public function updata($arr)
    {
        if ($arr == []) {
            error("updata参数为零");
        }
        if (is_array($arr[0])) {
            foreach ($arr as $val) {
                $str[] = $val[0] . $this->space . "=" . $this->space . markString($val[1]);
            }
            return $this->updata . $this->table . $this->set . implode(" , ", $str) . $this->where;

        } else {
            if (count($arr) == 2) {
                return $this->updata . $this->table . $this->set . $arr[0] . $this->space . "=" . $this->space . markString($arr[1]) . $this->where;
            } else {
                error("updata参数格式不对");
            }
        }

    }
    /**
     * 删除
     */
    public function delete()
    {
        return $this->delete . $this->table . $this->where;
    }
    /**
     * where语句
     */
    public function where($arr, $type = " AND ")
    {
        if ($arr == []) {
            error("where参数为零");
        }

        if (is_array($arr[0])) {
            foreach ($arr as $val) {
                $str[] = $this->stringJoin($val);
            }
            $str = implode($type, $str);
        } else {
            if (count($arr) == 3) {
                $str = $this->stringJoin(func_get_arg(0));
            } else {
                error("where参数格式不对");
            }
        }
        $this->where = ' WHERE ' . $str;
    }

    public function stringJoin($arr)
    {
        return $arr[0] . $this->space . $arr[1] . $this->space . markString($arr[2]) . $this->space;
    }
}