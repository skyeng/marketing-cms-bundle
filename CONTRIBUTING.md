## Версионирование

Придерживаемся принципов [семантического версионирования](https://semver.org/lang/ru/).

После мержа задачи в master обязательно добавляем тег версии. Например:
```bash
git checkout master
git pull origin master
git tag -a 1.0.2 -m "1.0.2"
git push --tags
```
