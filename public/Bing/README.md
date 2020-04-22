## 每日一图
为了方便随机切换图片，我们可以使用必应每日一图来参考写出接口，方便我们切换图片和收集图片。全新代码构建，全新界面。

[页面展示](https://api.xygeng.cn/Bing/index.html)

### 示例

#### 1、效果图
![bing](https://api.xygeng.cn/Bing/)

#### 2、每日一图

```html
<!-- html -->
<img src="https://api.xygeng.cn/Bing/" alt="">
```
```markdown
<!-- markdown -->
![bing](https://api.xygeng.cn/Bing/)
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