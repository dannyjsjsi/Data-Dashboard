#!/bin/bash

# 循环定期执行脚本
while true;
do
    # 执行脚本
    ansible-playbook gather.yml > /var/www/html/info.txt

    # 等待30秒
    sleep 30
done


