FROM mongo:7.0

COPY docker/mongodb/init.js /docker-entrypoint-initdb.d/init.js.template
COPY docker/mongodb/00-generate-init.sh /docker-entrypoint-initdb.d/00-generate-init.sh

RUN chmod +x /docker-entrypoint-initdb.d/00-generate-init.sh
