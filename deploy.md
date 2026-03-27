# Deploy liftED (Nginx on port 8080)

Minimal deploy flow for Ubuntu server with PHP-FPM + Nginx.

## 1) Install packages

```bash
sudo apt update
sudo apt install -y nginx php-fpm php-cli php-mysql php-mbstring php-xml php-curl php-zip unzip git composer
```

## 2) Copy project to server

From your local machine:

```bash
scp -r /path/to/liftED user@your-server-ip:/var/www/
```

On server:

```bash
cd /var/www/liftED
cp .env.example .env
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo chown -R www-data:www-data /var/www/liftED
sudo chmod -R 775 /var/www/liftED/storage /var/www/liftED/bootstrap/cache
```

## 3) Enable Nginx config

```bash
sudo cp /var/www/liftED/nginx.conf /etc/nginx/sites-available/lifted
sudo ln -s /etc/nginx/sites-available/lifted /etc/nginx/sites-enabled/lifted
sudo rm -f /etc/nginx/sites-enabled/default
```

If your server uses a different PHP version, update this line in `/etc/nginx/sites-available/lifted`:

```nginx
fastcgi_pass unix:/run/php/php8.2-fpm.sock;
```

## 4) Test and run Nginx

```bash
sudo nginx -t
sudo systemctl enable nginx
sudo systemctl restart nginx
sudo systemctl status nginx --no-pager
```

Your app should be available at:

`http://your-server-ip:8080`
