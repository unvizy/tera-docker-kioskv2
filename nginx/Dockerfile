FROM nginx:latest

COPY ./nginx/ssl/ /etc/ssl/
RUN chown -R 644 /etc/ssl/

COPY ./nginx/default.conf /etc/nginx/conf.d/
#COPY . /app

WORKDIR /app

EXPOSE 7044

