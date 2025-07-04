FROM ubuntu:22.04

RUN apt-get update && apt-get install -y squid openssh-server && \ 
    mkdir /var/run/sshd && echo 'root:toor' | chpasswd &&     sed -i 's/#PermitRootLogin prohibit-password/PermitRootLogin yes/' /etc/ssh/sshd_config

EXPOSE 22 3128

CMD service squid start && /usr/sbin/sshd -D
