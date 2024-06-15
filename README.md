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



## 功能介绍

页面展示：

![Snipaste_2024-06-15_13-23-25](E:\华为\HICA\pic\Snipaste_2024-06-15_13-23-25.png)

### 一、显示局域网内所有机器的运行/离线状态

![Snipaste_2024-06-15_12-31-27](E:\华为\HICA\pic\Snipaste_2024-06-15_12-31-27.png)

该功能会显示局域网内所有机器的IP地址，并且会显示所有机器的运行状态。若正在运行，则显示运行状态。若没有运行，则显示离线状态



### 二、显示运行中机器的详细信息

![Snipaste_2024-06-15_13-20-18](E:\华为\HICA\pic\Snipaste_2024-06-15_13-20-18.png)

	#### 1、IP信息

#### 2、机器名

#### 3、CPU使用率

#### 4、硬盘使用率

#### 5、内存使用率

#### 6、网络上传速率

#### 7、 网络下载速率

## 使用教程

下载必要插件

关闭防火墙,重启http服务

 ```
 systemctl stop firewalld
 setenforce 0
 systemctl restart httpd
 ```



打开浏览器，打开对应网页（网页自动每5s刷新一次）



------------------------------------------------------------------------------------------------------------------------------------------待后续更新