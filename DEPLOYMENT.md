# 🚀 Production Deployment Guide

This guide will help you deploy your Laravel application to a production server using Nginx on the host system and Docker containers for the application.

## 📋 Prerequisites

-   Ubuntu 20.04+ or similar Linux distribution
-   Docker and Docker Compose installed
-   Domain name pointing to your server
-   Root or sudo access

## 🔧 Server Setup

### 1. Install Required Software

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Install Nginx
sudo apt install nginx -y

# Install Certbot for SSL
sudo apt install certbot python3-certbot-nginx -y
```

### 2. Clone Your Application

```bash
# Clone your repository
git clone <your-repo-url> /var/www/cultureconnect
cd /var/www/cultureconnect

# Set proper permissions
sudo chown -R www-data:www-data /var/www/cultureconnect
sudo chmod -R 755 /var/www/cultureconnect
```

### 3. Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Edit environment for production
nano .env
```

Update these values in `.env`:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=cultureconnect
DB_USERNAME=cultureconnect
DB_PASSWORD=your_secure_password

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

JWT_SECRET=your_jwt_secret_key
```

### 4. Configure Nginx

```bash
# Copy Nginx configuration
sudo cp nginx/cultureconnect.conf /etc/nginx/sites-available/cultureconnect

# Update domain name in config
sudo sed -i 's/yourdomain.com/your-actual-domain.com/g' /etc/nginx/sites-available/cultureconnect

# Enable the site
sudo ln -s /etc/nginx/sites-available/cultureconnect /etc/nginx/sites-enabled/

# Remove default site
sudo rm /etc/nginx/sites-enabled/default

# Test Nginx configuration
sudo nginx -t

# Restart Nginx
sudo systemctl restart nginx
```

### 5. Start Docker Containers

```bash
# Build and start containers
docker-compose -f docker-compose.prod.yml up -d --build

# Run Laravel setup commands
docker-compose -f docker-compose.prod.yml exec app php artisan key:generate
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache
```

### 6. Setup SSL Certificate

```bash
# Get SSL certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Test automatic renewal
sudo certbot renew --dry-run
```

### 7. Configure Firewall

```bash
# Allow SSH, HTTP, and HTTPS
sudo ufw allow ssh
sudo ufw allow 'Nginx Full'
sudo ufw enable
```

## 🔄 Application Updates

When you need to update your application:

```bash
# Pull latest changes
git pull origin main

# Rebuild containers
docker-compose -f docker-compose.prod.yml up -d --build

# Run migrations
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# Clear caches
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache
```

## 📊 Monitoring & Logs

### View Application Logs

```bash
# Laravel logs
docker-compose -f docker-compose.prod.yml exec app tail -f storage/logs/laravel.log

# Nginx logs
sudo tail -f /var/log/nginx/cultureconnect_access.log
sudo tail -f /var/log/nginx/cultureconnect_error.log

# Container logs
docker-compose -f docker-compose.prod.yml logs -f app
```

### Health Checks

```bash
# Check container status
docker-compose -f docker-compose.prod.yml ps

# Check Nginx status
sudo systemctl status nginx

# Test application
curl -I https://yourdomain.com
```

## 🛡️ Security Considerations

1. **Environment Variables**: Never commit `.env` files
2. **Database Passwords**: Use strong, unique passwords
3. **JWT Secret**: Generate a secure JWT secret
4. **Firewall**: Only open necessary ports
5. **SSL**: Always use HTTPS in production
6. **Updates**: Keep system and containers updated

## 🚨 Troubleshooting

### Common Issues

1. **502 Bad Gateway**: Check if PHP-FPM container is running
2. **Database Connection**: Verify database credentials and container status
3. **Permission Issues**: Check file ownership and permissions
4. **SSL Issues**: Verify certificate installation and Nginx configuration

### Useful Commands

```bash
# Restart all services
sudo systemctl restart nginx
docker-compose -f docker-compose.prod.yml restart

# Check container logs
docker-compose -f docker-compose.prod.yml logs app

# Access container shell
docker-compose -f docker-compose.prod.yml exec app bash

# Check disk space
df -h

# Check memory usage
free -h
```

## 📈 Performance Optimization

1. **Enable OPcache** in PHP configuration
2. **Use Redis** for session storage and caching
3. **Configure Nginx** gzip compression
4. **Set up CDN** for static assets
5. **Monitor** application performance

Your Laravel application is now ready for production! 🎉
