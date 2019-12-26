# MyClub
小站后台管理系统（基于TP5.1）。

```bash
# 使用rabbitmq异步发送邮件小例子,实际应用还需要修改
# 需要修改mail.php的邮箱配置
# 需要配置好rabbitmq环境
# 在项目根目录下执行接收任务
php think rabbitmq
# 操作注册流程,注册的同时会异步发送一封邮件
```
