# MyClub

移到腾讯云开发者平台了，此处不再更新。

小站后台管理系统（基于TP5.1），仅作为小站系统使用功能的例子展示，不包含小站详细代码。

```bash
# 使用rabbitmq异步发送邮件的小例子,实际应用还需要修改
# 需要修改mail.php的邮箱配置
# 需要配置好rabbitmq环境
# 在项目根目录下执行 php think rabbitmq
# 操作注册流程,注册的同时会异步发送一封邮件

[root@localhost club]# php think rabbitmq
 [*] Waiting for messages. To exit press CTRL+C
Message has been sent [x] Received 2522950505@qq.com
^C
[root@localhost club]# 
```
