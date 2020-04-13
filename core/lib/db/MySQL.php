<?php
/*
 * @LastEditors: xygengcn
 * @LastEditTime: 2020-04-03 00:24:14
 */

namespace core\lib\db;

class MySQL
{
    private $result = '';
    private $pdo = null;
    private $db_config = "";
    public function __construct()
    {
        $this->db_config = config("database");
    }
    public function pdoStart()
    {
        $dsn = $this->db_config["database_type"] . ":host=" . $this->db_config["server"] . ":" . $this->db_config["port"] . ";dbname=" . $this->db_config["database_name"];
        try {
            $pdo = new \pdo($dsn, $this->db_config['username'], $this->db_config['password']);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            error("数据库连接失败: " . $e->getMessage(), $e->getCode());
        }
        $pdo->query("set names " . $this->db_config["charset"]);
        return $pdo;
    }
    /**
     * 数据断开
     */
    public function pdoStop()
    {
        $this->pdo = null;
    }
    /**
     * 单条数据查询
     */
    public function pdoSelect($sql)
    {
        try {
            $pdo = $this->pdoStart();
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error("查询失败: " . $e->getMessage(), $e->getCode());
        }
    }

    public function pdoExec($sql, $row = [])
    {
        $lastInsertId = [];
        try {
            $pdo = $this->pdoStart();
            if (is_string($sql)) {
                $stmt = $pdo->prepare($sql);
                $stmt->setFetchMode(\PDO::FETCH_ASSOC);
                if ($row != [] && $row != null) {
                    foreach ($row[1] as $key) {
                        foreach ($key as $k => $i) {
                            $stmt->bindValue(":" . $row[0][$k], $i);
                        }
                        $stmt->execute();
                        $lastInsertId[] = $pdo->lastInsertId();
                    }
                    return $lastInsertId;
                } else {
                    $stmt->execute();
                    return $pdo->lastInsertId() ? $pdo->lastInsertId() : $stmt->rowcount();
                }
            }
            if (is_array($sql)) {
                foreach ($sql as $item) {
                    $stmt = $pdo->prepare($item);
                    $stmt->setFetchMode(\PDO::FETCH_ASSOC);
                    $stmt->execute();
                    $lastInsertId[] = $pdo->lastInsertId();
                }
                return $lastInsertId;
            }
            return false;

        } catch (\PDOException $e) {
            error("操作失败: " . $e->getMessage(), $e->getCode());
        }
    }
    /**
     * 多数据查询
     */
    public function pdoSelects($table, $data2, $limit, $desc = 'id')
    {
        $pdo = $this->pdo;
        if ($pdo != null) {
            $data = array();
            $sql = "select * from " . $table . " where " . $data2['key'] . " = '" . $data2['value'] . "'  ORDER BY " . $desc . " DESC LIMIT " . $limit['min'] . "," . $limit['max'];
            foreach ($pdo->query($sql) as $row) {
                array_push($data, $row);
            }
            $this->result = $data;
        }
        return $this;
    }
    /**
     * 单表查询
     */
    public function pdoSelectAll($table, $limit, $desc = 'id')
    {
        $pdo = $this->pdo;
        if ($pdo != null) {
            $data = array();
            $sql = "select * from " . $table . " ORDER BY " . $desc . " DESC LIMIT " . $limit['min'] . "," . $limit['max'];
            foreach ($pdo->query($sql) as $row) {
                array_push($data, $row);
            }
            $this->result = $data;
        }
        return $this;
    }
    /**
     * 更新数据
     */
    public function pdoUpdate($table, $data)
    {
        $pdo = $this->pdo;
        $sql = null;
        foreach ($data as $key => $value) {
            if ($key != 'id') {
                $sql = $sql . $key . " ='" . $value . "',";
            }
        }
        $sql = rtrim($sql, ",");
        $sql = "UPDATE " . $table . " SET " . $sql . " WHERE `id`=" . $data['id'];
        $stmt = $pdo->prepare($sql);
        $num = $stmt->execute();
        $count = $stmt->rowCount(); //受影响行数
        $this->result = $count;
        return $this;
    }
    /**
     * 单表数据统计
     */
    public function pdoCount($table)
    {
        $pdo = $this->pdo;
        $sql = "select count(*) num from " . $table;
        foreach ($pdo->query($sql) as $row) {
            $this->result = $row;
        }
        return $this;
    }
    /**
     * 多表统计
     */
    public function pdoCounts()
    {
        global $dbName;
        $pdo = $this->pdo;
        $sql = "select TABLE_NAME,TABLE_ROWS from information_schema.tables where table_schema='" . $dbName . "'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $this->result = $stmt->fetchAll();
        return $this;
    }
}