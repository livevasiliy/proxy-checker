# Proxy checker

## Начало использования

### Клонируйте проект
```bash
git clone https://github.com/livevasiliy/proxy-checker.git
```

### Скопируйте .env файл
```bash
cp .env.example .env
```

### Запустите docker контейнер
```bash
docker compose up [-d] ## Добавьте флаг -d если хотите запустить в фоне
```

### Зайдите внутрь контейнера и запустите следующие команды
```bash
docker compose exec web bash
composer install
php artisan key:generate
php artisan optimize:clear
```

