# LMS Docker Makefile
# Usage: make <command>

.PHONY: help build up down restart logs shell migrate seed fresh

# Show help
help:
	@echo "Available commands:"
	@echo "  make build      - Build Docker images"
	@echo "  make up         - Start all containers"
	@echo "  make down       - Stop all containers"
	@echo "  make restart    - Restart all containers"
	@echo "  make logs       - View container logs"
	@echo "  make shell      - Open shell in app container"
	@echo "  make migrate    - Run all migrations"
	@echo "  make seed       - Run database seeders"
	@echo "  make fresh      - Fresh migration with seed"
	@echo "  make setup      - Full setup (build + up + migrate)"

# Build Docker images
build:
	docker-compose build

# Start containers
up:
	docker-compose up -d

# Stop containers
down:
	docker-compose down

# Restart containers
restart:
	docker-compose restart

# View logs
logs:
	docker-compose logs -f

# Open shell in app container
shell:
	docker-compose exec app bash

# Run migrations for all databases
migrate:
	docker-compose exec app php artisan migrate --database=mysql
	docker-compose exec app php artisan migrate --database=guru --path=database/migrations/multi_db/2025_12_19_000001_create_guru_database_tables.php
	docker-compose exec app php artisan migrate --database=siswa --path=database/migrations/multi_db/2025_12_19_000002_create_siswa_database_tables.php
	docker-compose exec app php artisan migrate --database=admin --path=database/migrations/multi_db/2025_12_19_000003_create_admin_database_tables.php

# Run seeders
seed:
	docker-compose exec app php artisan db:seed

# Fresh migration with seed
fresh:
	docker-compose exec app php artisan migrate:fresh --database=mysql --seed
	docker-compose exec app php artisan migrate --database=guru --path=database/migrations/multi_db/2025_12_19_000001_create_guru_database_tables.php
	docker-compose exec app php artisan migrate --database=siswa --path=database/migrations/multi_db/2025_12_19_000002_create_siswa_database_tables.php
	docker-compose exec app php artisan migrate --database=admin --path=database/migrations/multi_db/2025_12_19_000003_create_admin_database_tables.php

# Full setup
setup: build up
	@echo "Waiting for databases to be ready..."
	sleep 10
	$(MAKE) migrate
	@echo "Setup complete! Access the app at http://localhost:8000"

# Clear Laravel caches
clear:
	docker-compose exec app php artisan optimize:clear

# Install dependencies
install:
	docker-compose exec app composer install
	docker-compose exec app npm install
	docker-compose exec app npm run build
