#!/bin/bash

# 🚀 Laravel Production Deployment Script
# This script automates the deployment process

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
APP_DIR="/var/www/cultureconnect"
COMPOSE_FILE="docker-compose.prod.yml"
BACKUP_DIR="/var/backups/cultureconnect"

echo -e "${BLUE}🚀 Starting Laravel Deployment...${NC}"

# Function to print colored output
print_status() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Check if running as root or with sudo
if [ "$EUID" -ne 0 ]; then
    print_error "Please run this script with sudo"
    exit 1
fi

# Navigate to application directory
cd $APP_DIR

print_status "Navigated to application directory: $APP_DIR"

# Create backup directory if it doesn't exist
mkdir -p $BACKUP_DIR

# Backup database before deployment
print_status "Creating database backup..."
docker-compose -f $COMPOSE_FILE exec -T db pg_dump -U cultureconnect cultureconnect > $BACKUP_DIR/backup_$(date +%Y%m%d_%H%M%S).sql

# Pull latest changes
print_status "Pulling latest changes from Git..."
git pull origin main

# Set proper permissions
print_status "Setting file permissions..."
chown -R www-data:www-data $APP_DIR
chmod -R 755 $APP_DIR
chmod -R 775 $APP_DIR/storage
chmod -R 775 $APP_DIR/bootstrap/cache

# Build and start containers
print_status "Building and starting Docker containers..."
docker-compose -f $COMPOSE_FILE up -d --build

# Wait for containers to be ready
print_status "Waiting for containers to be ready..."
sleep 10

# Run Laravel commands
print_status "Running Laravel setup commands..."

# Generate application key if not exists
docker-compose -f $COMPOSE_FILE exec app php artisan key:generate --force

# Run database migrations
docker-compose -f $COMPOSE_FILE exec app php artisan migrate --force

# Clear and cache configurations
docker-compose -f $COMPOSE_FILE exec app php artisan config:clear
docker-compose -f $COMPOSE_FILE exec app php artisan config:cache

# Clear and cache routes
docker-compose -f $COMPOSE_FILE exec app php artisan route:clear
docker-compose -f $COMPOSE_FILE exec app php artisan route:cache

# Clear and cache views
docker-compose -f $COMPOSE_FILE exec app php artisan view:clear
docker-compose -f $COMPOSE_FILE exec app php artisan view:cache

# Clear application cache
docker-compose -f $COMPOSE_FILE exec app php artisan cache:clear

# Restart Nginx
print_status "Restarting Nginx..."
systemctl restart nginx

# Check if services are running
print_status "Checking service status..."

# Check Docker containers
if docker-compose -f $COMPOSE_FILE ps | grep -q "Up"; then
    print_status "Docker containers are running"
else
    print_error "Some Docker containers are not running"
    docker-compose -f $COMPOSE_FILE ps
    exit 1
fi

# Check Nginx
if systemctl is-active --quiet nginx; then
    print_status "Nginx is running"
else
    print_error "Nginx is not running"
    systemctl status nginx
    exit 1
fi

# Test application
print_status "Testing application..."
if curl -f -s http://localhost > /dev/null; then
    print_status "Application is responding"
else
    print_warning "Application might not be responding correctly"
fi

# Clean up old Docker images
print_status "Cleaning up old Docker images..."
docker image prune -f

# Clean up old backups (keep last 7 days)
print_status "Cleaning up old backups..."
find $BACKUP_DIR -name "backup_*.sql" -mtime +7 -delete

print_status "🎉 Deployment completed successfully!"

# Display useful information
echo -e "${BLUE}📊 Deployment Summary:${NC}"
echo -e "Application URL: https://yourdomain.com"
echo -e "Container Status: $(docker-compose -f $COMPOSE_FILE ps --services | wc -l) services running"
echo -e "Backup Location: $BACKUP_DIR"
echo -e "Logs: docker-compose -f $COMPOSE_FILE logs -f"

echo -e "${GREEN}✅ Deployment finished at $(date)${NC}"
