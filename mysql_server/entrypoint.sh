#!/bin/bash

# Inicia MySQL
service mysql start

# Espera a que arranque MySQL
sleep 5

# Comprobamos si la tabla ya existe
if ! mysql -uroot -ptoor -e "USE insegura; SHOW TABLES LIKE 'usuarios';" | grep -q usuarios; then
    echo "Importando base de datos..."
    mysql -uroot -ptoor < /.init.sql
    rm -f /.init.sql
else
    echo "Base de datos ya inicializada."
fi

# Ejecuta SSH en primer plano
exec /usr/sbin/sshd -D
