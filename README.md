## OpenApi

开源api系统，将会汇集搜集开源的所有api接口，个人开发简版操作



[TOC]



### 框架结构

1. app -> 用户定义控制器、模板、配置文件和路由
2. core -> 框架核心
3. public -> 界面文件
4. index.php -> 入口文件
5. nginx.htaccess -> nginx伪静态


### 控制器操作

在app\controller新建文件，类名和文件保持一致。如果使用模板文件model，请在model文件夹新建文件保持类名和文件一致。不建议类名和函数名一样。

```php
<?php
//命名空间必须
namespace app\controller;
/**
 * 请保持文件名和类名一致
 *
 * 组件引用use  \app\model\+名字  调用 test::test()
 * 如果名字和controller一样需要如下调用\app\model\test::test();
 *
 */
class test
{
    public function index()
    {
        echo "123";
        //test::test()
    }
    public function test($key)
    {
        echo $key;
        //\app\model\test::test();
    }
}
```

函数调用：

```php
类名::函数名()  #注意命名空间
```



### 路由操作

路由默认先遍历路由模板，若无再执行基本路由

#### 基本路由

```
http://YourDomain.com/控制器名/函数名/[参数]/[参数]...

http://YourDomain.com/控制器名  =>默认 函数名是index，执行控制器的index()函数
```

#### 路由模板

```
http://YourDomain.com/路由名/[参数]/[参数]...
```

路由名需要在app\route的route文件定义：

```php
return [
    "/test" => "test@index",
    "/test/test2" => "test@test",
];
```

例子：

```
http://YourDomain.com/test/123/123

将会对应访问：test类的index函数，传入(123,123)两个参数
```

### 数据库操作



#### 数据库连接

在app\config的database文件设置数据库连接信息

```php
return array(
    "db_Type" => "mysql", //只支持mysql
    "host" => "localhost:3306", //数据库地址加端口
    "dbName" => "api", //数据库名
    "userName" => "root", //用户名
    "password" => "root", //密码
);
```



#### 插入数据

单条插入数据

```php
DB("表名")->insert([key => value,key =>value]);

DB("test")->insert(["name" => "/21123/test"]);
```

多条数据插入

```php
#使用insert函数
DB("表名")->insert([key => value,key =>value]，[key => value,key =>value]);

#示例：
DB("test")->insert(["name" => "11"], ["name" => "22", "remark" => "2"], ["name" => "33", "remark" => "3"]);

#使用inserts函数，第一个数据是放插入属性，后面每个数组是一条数据。
DB("表名")->inserts([key,key...],[value,value...],[value,value...]);

#示例
DB("test")->inserts(["name", "remark"], ["aa", "bb"], ["cc", "dd"]);
```



#### 删除数据

```php
#删除 id=75的数据，具体where的用法在后面

DB("test")->where(["id", "=", "75"])->delete();
DB("test")->where("id", "=", "75")->delete();
```

#### 更新数据

```php
#更新id=77的数据的remark属性为88，updata一个数组一个属性更改，具体where的用法在后面
DB("test")->where("id", "=", "77")->updata(["remark", "88"]);

#更改remark和name为88
DB("test")->where("id", "=", "77")->updata(["remark", "88"]，["name","88"]);
```

#### 查询数据

```php
#查询全表

DB("test")->select();

#只查询全表某些属性

DB("test")->select("id", "name");

#使用where，具体用法在后面

DB("test")->where(["id", ">", "10"])->select();
```

#### where语句

简单where语句:

单个属性，两者都可

```php
DB("test")->where(["id", ">", "10"])->select();

DB("test")->where("id", ">", "10")->select();
```

多个属性，where默认and语句

```php
DB("test")->where(["id", ">", "10"], ["id", ">", "10"])->select();
```

and语句

```php
DB("test")->and(["id", "=", "80"], ["id", "=", "81"])->select()
```

or语句

```php
DB("test")->or(["id", "=", "80"], ["id", "=", "81"])->select()
```

orderby 语句

```php
#单个，默认升序ASC
DB("test")->where("id", ">", "80")->order('id DESC')->select()

#多个参数，设置倒序
DB("test")->order('id DESC',"...")->select()
```



### 更新记录

1. 2020-04-11 上传项目
2. 2020-04-12 增加bing每日一图接口
3. 2020-04-13 增加测试API平台
