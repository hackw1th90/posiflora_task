# POSIFLORA test suite 260408

 - [Task details](task.pdf) [PDF!!!]
 - use as https://127.0.0.1:6503/shops/33/growth/telegram [WebApp]
 - That is SUPER-PACK - just seed data after `docker-compose up...` (everything inc migrations fires up automatically) 

# Run project
```shell
git clone git@github.com:hackw1th90/posiflora_task.git
cd devops && docker-compose up -d
```

# Seed data (inside container)
```shell
# seed shop and orders
bin/console posiflora:seed-data
````

# Additional
```shell
# regen SSL
cd devops/resources/nginx/ssl && rm -rf server.*
openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout server.key -out server.crt
```

# Test it with curl

```shell
# modify tg bot credetials
curl -k 'https://127.0.0.1:6503/shops/33/telegram/connect' \
  -H 'Accept: application/json, text/plain, */*' \
  -H 'Content-Type: application/json;charset=UTF-8' \
  --data-raw '{"botToken":"...","chatId":"-132123123123","enabled":true}'

# add orders
curl -k 'https://127.0.0.1:6503/shops/33/orders' \
  -H 'Accept: application/json, text/plain, */*' \
  -H 'Content-Type: application/json;charset=UTF-8' \
  --data-raw '{"number":"X-213234","total":123,"customerName":"User"}'

# get status
curl -k 'https://127.0.0.1:6503/shops/33/telegram/status' 
```
