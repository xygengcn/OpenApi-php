## 万年历接口
万年历接口，提供当日的阳历、阴历日期和当天节日

[页面展示](https://api.xygeng.cn/day/index.html)

### 接口详细

接口：GET请求   [API测试平台](https://api.xygeng.cn/test.html)

```
https://api.xygeng.cn/day
```

结果：

```json
{
    "code": 200,
    "data": {
        "solar": {
            "year": "2020",
            "month": "07",
            "date": "07",
            "day": "星期二"
        },
        "lunar": {
            "year": "庚子",
            "animal": "鼠",
            "month": "五月",
            "date": "十七"
        },
        "festival": []
    }
}
```

