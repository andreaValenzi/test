# test

commands to run:

- echo '127.0.0.1       local.test.com' | sudo tee -a /etc/hosts
- docker-compose up

the root url:
http://local.test.com:9080

how to create a transaction:
curl -X PUT -H "Content-Type: application/json" --data '{"product":{"id":"2"},"amount":"5","currency":"coins","user_id":"5"}' http://local.test.com:9080/transactions