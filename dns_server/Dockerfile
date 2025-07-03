FROM ubuntu:22.04

RUN apt-get update && apt-get install -y bind9 openssh-server && \ 
    mkdir /var/run/sshd && echo 'root:toor' | chpasswd &&     sed -i 's/#PermitRootLogin prohibit-password/PermitRootLogin yes/' /etc/ssh/sshd_config

COPY named.conf.local /etc/bind/named.conf.local
COPY db.lab.local /etc/bind/db.lab.local

EXPOSE 22 53

CMD service bind9 start && /usr/sbin/sshd -D
