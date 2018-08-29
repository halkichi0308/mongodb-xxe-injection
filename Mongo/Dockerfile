#FROM mongo:3.6.7-jessie
FROM debian:latest
RUN apt-get update && apt-get install mongodb -y
#COPY conf/init.js /docker-entrypoint-initdb.d/init.js
#COPY conf/mogodb.config /data/configdb/mogodb.config
#RUN echo 'var str = 1;' > /docker-entrypoint-initdb.d/test.js
RUN mkdir /data && mkdir /data/db
EXPOSE 27017
ENTRYPOINT ["/usr/bin/mongod"]
