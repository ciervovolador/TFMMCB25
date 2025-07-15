FROM ubuntu:22.04

RUN apt-get update && apt-get install -y \
    mysql-server \
    openssh-server \
    && mkdir /var/run/sshd \
    && echo 'root:toor' | chpasswd \
    && sed -i 's/#PermitRootLogin prohibit-password/PermitRootLogin yes/' /etc/ssh/sshd_config

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 22 3306

CMD ["/entrypoint.sh"]
