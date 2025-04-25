```bash
docker compose up
docker compose exec bot php app.php migrate
docker compose exec bot composer test
```

See https://github.com/shanginn/cycle-orm-relation-bug-example/blob/master/tests/Entity/Usage/UsageTest.php