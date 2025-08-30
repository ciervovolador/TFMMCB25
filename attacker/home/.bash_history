ping inseguracorp.com
ping https://www.inseguracorp.com
curl https://www.inseguracorp.com
curl https://www.inseguracorp.com
ng https://www.inseguracorp.com
ping: https://www.inseguracorp.com: Name or service not known
root@f0668036ad69:~# curl https://www.inseguracorp.com
curl: (60) SSL certificate problem: self signed certificate
More details here: https://curl.haxx.se/docs/sslcerts.html
curl failed to verify the legitimacy of the server and therefore could not
establish a secure connection to it. To learn more about this situation and
how to fix it, please visit the web page mentioned above.
root@f0668036ad69:~# curl https://www.inseguracorp.com
curl -k https://www.inseguracorp.com
