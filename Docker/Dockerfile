FROM --platform=linux/amd64 docker.io/bitnami/magento:2

RUN echo "Mutex posixsem" >> /opt/bitnami/apache/conf/httpd.conf

COPY "docker-entrypoint.sh" "/bitnami/docker-entrypoint.sh"
RUN [ "chmod", "+x", "/bitnami/docker-entrypoint.sh" ]
ENTRYPOINT [ "/bitnami/docker-entrypoint.sh" ]
CMD [ "/opt/bitnami/scripts/magento/run.sh" ]
