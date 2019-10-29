# 简单示例怎么获取，提交profiling数据到工具去分析

在项目的入口index.php 开头引入MySqlProfiling类（或者通过composer加载）

然后修改一下MySqlProfiling类里面的TODO项，

index.php里面加入以下代码，注意命名空间

```php
$profiling=new MySqlProfiling();
$profiling->start();
```

其他情况类似，都是在程序执行刚开始执行这段代码，这样才能完整的监控到整个流程的sql信息
