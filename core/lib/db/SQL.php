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
    private $where = "";
    private $order = "";
    private $limit = "";
    private $table = "";
    private $sql = "";
    private $join = "";

    public function __construct($table)
    {
        $this->table = $table;
    }

    /**
     * 查询
     */
    public function select($arr)
    {
        if ($this->sql != "") {
            return $this->sql;
        }
        $key = "*";
        if ($arr != [] && is_array($arr)) {
            $key = implode($arr, ",");
        }
        if (is_string($arr)) {
            $key = $arr;
        }
        return $this->select . $key . $this->from . $this->table . $this->join . $this->where . $this->order . $this->limit;
    }
    /**
     * 插入
     */
    public function insert($data)
    {
        if ($this->sql != "") {
            return $this->sql;
        }
        if ($data != []) {
            foreach ($data as $index) {
                $keyArr = [];
                $valueArr = [];
                foreach ($index as $key => $value) {
                    $keyArr[] = $key;
                    $valueArr[] = str_rl($value);
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
        if ($this->sql != "") {
            return $this->sql;
        }
        if ($arr == []) {
            error("updata参数为零");
        }
        if (is_array($arr[0])) {
            foreach ($arr as $val) {
                $str[] = $val[0] . $this->space . "=" . $this->space . str_rl($val[1]);
            }
            return $this->updata . $this->table . $this->set . implode(" , ", $str) . $this->where;

        } else {
            if (count($arr) == 2) {
                return $this->updata . $this->table . $this->set . $arr[0] . $this->space . "=" . $this->space . str_rl($arr[1]) . $this->where;
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
        if ($this->sql != "") {
            return $this->sql;
        }
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
        if (is_string($arr)) {
            $str = $arr;
        }
        if (is_array($arr[0])) {
            foreach ($arr as $val) {
                $str[] = $this->stringJoin($val);
            }
            $str = implode($type, $str);
        }
        if (is_array($arr) && count($arr) == 3) {
            $str = $this->stringJoin(func_get_arg(0));
        }
        if (is_array($arr) && count($arr) != 3) {
            error("where参数格式不对");
        }
        $this->where = ' WHERE ' . $str;
    }

    public function stringJoin($arr)
    {
        return $arr[0] . $this->space . $arr[1] . $this->space . str_rl($arr[2]) . $this->space;
    }

    /**
     * order
     */
    public function order($arr)
    {
        if ($arr != []) {
            $this->order = " ORDER BY " . implode(",", $arr);
        }

    }
    /**
     * limit
     */
    public function limit($arr)
    {
        if ($arr != []) {
            $this->limit = " LIMIT " . implode(",", $arr);
        }

    }
    /**
     * 随机函数
     */
    public function rand($limit, $row)
    {

        $this->join(" AS t1 JOIN (SELECT ROUND(RAND() * ((" . $this->select("MAX(" . str_clean($row) . ")") . ")-(" . $this->select("MIN(" . str_clean($row) . ")") . "))+(" . $this->select("MIN(" . str_clean($row) . ")") . ")) AS id) AS t2");
        $this->where("t1." . str_clean($row) . ">=t2." . str_clean($row));
        $this->limit([$limit]);
        $this->order(["t1." . str_clean($row)]);
    }

    public function join($sql)
    {
        $this->join = $this->space . $sql . $this->space;
    }

    public function sql($sql)
    {
        $this->sql = $sql;

    }
}