# CultureConnect Laravel - Docker Setup

## 🐳 Fully Dockerized Laravel Application

This Laravel application is now fully dockerized with a complete development environment.

## 🚀 Quick Start

### 1. Build and Start Services
```bash
# Build and start all services
docker-compose up -d --build

# View logs
docker-compose logs -f
```

### 2. Access the Application
- **Application**: http://localhost:8000
- **API Documentation**: http://localhost:8000/api/documentation
- **MailHog (Email Testing)**: http://localhost:8025

### 3. Database Setup
```bash
# Run migrations
docker-compose exec app php artisan migrate

# Generate JWT secret (if needed)
docker-compose exec app php artisan jwt:secret
```

## 🏗️ Architecture

### Services
- **app**: Laravel PHP-FPM application
- **nginx**: Web server and reverse proxy
- **db**: PostgreSQL database
- **redis**: Redis cache and session store
- **mailhog**: Email testing service

### Ports
- `8000`: Nginx (Web server)
- `5432`: PostgreSQL
- `6379`: Redis
- `8025`: MailHog Web UI
- `1025`: MailHog SMTP

## 🛠️ Development Commands

### Laravel Commands
```bash
# Run artisan commands
docker-compose exec app php artisan [command]

# Examples:
docker-compose exec app php artisan migrate
docker-compose exec app php artisan tinker
docker-compose exec app php artisan route:list
```

### Composer Commands
```bash
# Install dependencies
docker-compose exec app composer install

# Add new packages
docker-compose exec app composer require [package]
```

### Database Commands
```bash
# Access PostgreSQL
docker-compose exec db psql -U cultureconnect -d cultureconnect

# Backup database
docker-compose exec db pg_dump -U cultureconnect cultureconnect > backup.sql
```

## 🔧 Configuration

### Environment Variables
The application uses environment variables for configuration. Key variables:

- `DB_HOST=db` (PostgreSQL container)
- `REDIS_HOST=redis` (Redis container)
- `JWT_SECRET` (JWT authentication secret)

### File Permissions
The entrypoint script automatically sets proper permissions for Laravel:
- Storage directory: `775`
- Bootstrap cache: `775`
- Owner: `www-data:www-data`

## 📁 Docker Files

- `Dockerfile`: PHP-FPM application container
- `docker-compose.yml`: Main services configuration
- `docker-compose.override.yml`: Environment overrides
- `nginx.conf`: Nginx configuration
- `docker/php/local.ini`: PHP configuration
- `docker-entrypoint.sh`: Container startup script

## 🧪 Testing

### API Testing
```bash
# Test OTP request
curl -X POST http://localhost:8000/api/auth/request-otp \
  -H "Content-Type: application/json" \
  -H "Accept-Language: en" \
  -d '{"identifier": "test@example.com", "provider": "email"}'

# Test with Arabic
curl -X POST http://localhost:8000/api/auth/request-otp \
  -H "Content-Type: application/json" \
  -H "Accept-Language: ar" \
  -d '{"identifier": "test@example.com", "provider": "email"}'
```

## 🚨 Troubleshooting

### Common Issues

1. **Permission Errors**
   ```bash
   # Fix permissions
   docker-compose exec app chown -R www-data:www-data /var/www/storage
   docker-compose exec app chmod -R 775 /var/www/storage
   ```

2. **Database Connection Issues**
   ```bash
   # Check database status
   docker-compose exec db pg_isready -U cultureconnect
   ```

3. **Clear Caches**
   ```bash
   # Clear all caches
   docker-compose exec app php artisan cache:clear
   docker-compose exec app php artisan config:clear
   docker-compose exec app php artisan route:clear
   docker-compose exec app php artisan view:clear
   ```

### Logs
```bash
# View application logs
docker-compose logs app

# View nginx logs
docker-compose logs nginx

# View database logs
docker-compose logs db
```

## 🛑 Stop Services
```bash
# Stop all services
docker-compose down

# Stop and remove volumes (WARNING: This will delete database data)
docker-compose down -v
```

## 🔄 Production Deployment

For production deployment, consider:
1. Using production-optimized images
2. Setting up SSL certificates
3. Configuring proper environment variables
4. Setting up database backups
5. Using external Redis/PostgreSQL services

---

**Your Laravel application is now fully dockerized! 🎉**
