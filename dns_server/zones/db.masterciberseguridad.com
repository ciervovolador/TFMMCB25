$TTL    604800
@       IN      SOA     ns.masterciberseguridad.com. admin.masterciberseguridad.com. (
                             3         ; Serial
                        604800         ; Refresh
                         86400         ; Retry
                       2419200         ; Expire
                         604800 )       ; Negative Cache TTL
;
@       IN      NS      ns.masterciberseguridad.com.
ns      IN      A       172.20.0.10
mail    IN      A       172.20.0.13
@       IN      MX 10   mail.masterciberseguridad.com.
