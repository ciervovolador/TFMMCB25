FROM ubuntu:22.04

RUN apt-get update && apt-get install -y \
    mysql-server \
    openssh-server \
    && mkdir /var/run/sshd \
    && echo 'root:toor' | chpasswd \
    && sed -i 's/#PermitRootLogin prohibit-password/PermitRootLogin yes/' /etc/ssh/sshd_config \
    # Cambiar bind-address para que MySQL escuche en todas las interfaces
    && sed -i 's/^bind-address\s*=.*/bind-address = 0.0.0.0/' /etc/mysql/mysql.conf.d/mysqld.cnf

COPY entrypoint.sh /entrypoint.sh
COPY init.sql /init.sql
RUN chmod +x /entrypoint.sh

EXPOSE 22 3306

CMD ["/entrypoint.sh"]
