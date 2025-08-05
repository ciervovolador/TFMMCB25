$TTL 604800
@   IN  SOA ns1.inseguracorp.com. admin.inseguracorp.com. (
          4     ; Serial  
     604800     ; Refresh
      86400     ; Retry
    2419200     ; Expire
     604800 )   ; Negative Cache TTL

@    IN  NS      ns1.inseguracorp.com.
ns1  IN  A       203.0.113.20

@    IN  A       203.0.113.20         ; inseguracorp.com → Apache
www  IN  A       172.20.0.11          ; www.inseguracorp.com → Apache
