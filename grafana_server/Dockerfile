FROM grafana/grafana:8.3.0

USER root

RUN apk add --no-cache curl vim sqlite

RUN echo "FLAG{Buen trabajo. ¡me has encontrado!}" > /etc/flag.txt

COPY grafana.db /var/lib/grafana/grafana.db

RUN chown 472:472 /var/lib/grafana/grafana.db
RUN chmod 644 /var/lib/grafana/grafana.db

EXPOSE 3000
