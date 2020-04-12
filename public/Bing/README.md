## 每日一图


### 示例

#### 1、效果图
![bing](http://api.cn/Bing/)

#### 2、每日一图

```html
<!-- html -->
<img src="https://api.xygeng.cn/Bing/" alt="">
```
```markdown
<!-- markdown -->
![bing](http://api.cn/Bing/)
```

#### 3、一周七图

接口：GET请求   [API测试平台](https://api.xygeng.cn/test.html)

```
https://api.xygeng.cn/Bing/week/
```

结果：

```json
{
    "code": 200,
    "data": [
        "http://cn.bing.c224_1920x1080.jpg&rf=LaDigue_1920x1080.jpg",
        "http://cn.bing.c224_1920x1080.jpg&rf=LaDigue_1920x1080.jpg",
        "http://cn.bing.c224_1920x1080.jpg&rf=LaDigue_1920x1080.jpg",
        "http://cn.bing.c224_1920x1080.jpg&rf=LaDigue_1920x1080.jpg",
        "http://cn.bing.c224_1920x1080.jpg&rf=LaDigue_1920x1080.jpg",
        "http://cn.bing.c224_1920x1080.jpg&rf=LaDigue_1920x1080.jpg",
        "http://cn.bing.c224_1920x1080.jpg&rf=LaDigue_1920x1080.jpg"
    ]
}
```