# 数据大屏

## 主机插件

1.自动获取ansible组内的ip地址

2.自动识别主机是否在线（正则表达式）

3.自动循环获取主机的状态信息（运行/停止、CPU使用率、硬盘使用率、网络上传下载速率监控等）

4.网页自动刷新

### 主机插件

```python
yum install epel-release -y
yum install libselinux-python -y
yum install ansible -y
yum install iperf3 -y
yum install httpd -y
```

1.添加局域网内的ip地址到`/etc/ansible/hosts` 中

2.将`httpd`服务加入到防火墙白名单中，并设置`setenforce 0`

3.后台运行`run_playbook.sh`

------------------------------------------------------------------------------------------------------------------------------------------待后续更新