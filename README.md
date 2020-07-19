## OpenApi

开源api系统，将会汇集搜集开源的所有api接口，个人开发简版操作



![](https://img.shields.io/badge/PHP->=7-<blue>.svg) ![](https://img.shields.io/badge/license-MIT-<blue>.svg) ![](https://img.shields.io/badge/Medoo-v1.6-<blue>.svg) ![](https://img.shields.io/badge/MySQL-v1.15.11-<blue>.svg) ![](https://img.shields.io/badge/Redis-v3.0.0-<blue>.svg)


[TOC]

### 安装
1. 直接把工程放到网站
2. 配置nginx伪静态.htaccess文件【重点】
3. 把config.inc.php改成config.php【重点】
4. 配置数据库，redis地址账号密码等【重点】

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

### 内置数据库


#### 数据库连接

在app\config的database文件设置数据库连接信息

```php
return [
    "database_type" => "mysql", //只支持mysql
    "server" => "localhost", //数据库地址
    "port" => 3306, //默认3306
    "database_name" => "api", //数据库名
    "username" => "root", //用户名
    "password" => "root", //密码

    //不了解下面勿动
    'charset' => 'utf8',
    'prefix' => '',
    'logging' => true,
    'socket' => '/tmp/mysql.sock',
    'option' => [
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
    ],
    'command' => [
        'SET SQL_MODE=ANSI_QUOTES',
    ]
];
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

limit 语句

```php
DB("test")->limit(0,1)->select()
```


### Medoo数据库

#### 基础操作

在app\config的database文件设置数据库连接信息

```php
$db = Medoo();//获取对象，注意没有new

//查询全表
$db->select("test", "*");

//查询个别
$db->select("account", "user_name");
```

#### 高级用法

[详细操作手册](https://medoo.lvtao.net/1.2/doc.php)

### 内置验证函数

#### 本地域名验证
```php
Auth("domain");//只能系统请求
```
#### token验证

如果接口出现没有权限，基本来自没有token验证，需要用户登录注册

```php
Auth();//函数直接使用，可以用header，cookie，get获取token，验证redis即可
```

### 全局函数

#### 常量输出

```php
_e($data);# $data支持数字，字符串，对象，数组，null
```

#### 接口返回

```php
response($data,$code=200) #$data支持数字，数组，对象数组，字符串
    
{
    "code": 200,
    "data": {
        "id": "807",
        "tag": "漫画",
        "origin": "《萤火之森》",
        "content": "其实美丽的故事都是没有结局的，只因为它没有结局所以才会美丽。",
        "datetime": "1548230343"
    }
}
```



#### 错误提示

```php
//抛出错误，会终止程序运行

error($msg,$code=404); # $msg是错误提示信息，$code是错误代码，默认404

//返回
{
    "code": 404,
    "error": "控制器:\\app\\controller\\one5 未定义文件"
}
```

#### 时间戳

``` php
# 默认返回13位,可以输出10位

timestamp($num =13);
```

#### 服务器网址

```php
site_url();//https://xygeng.cn
```

#### 请求来源域名

```php
getOriginDomain();#https://api.xygeng.cn
```

#### 去特殊符号

```php
#字符串去特殊符号
str_clean($str);
```

#### 爬取函数

```php
#单条网址爬取,返回字符串
curl_fetch($url, $cookie = '', $referer = '', $timeout = 10, $ishead = 0)

#多条网址，返回数组
curl_multi_fetch($urlarr = array(), $timeout = 10)
```