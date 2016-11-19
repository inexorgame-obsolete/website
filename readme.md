# Website
This is a repository for our website, which is based on the [Fat-Free Framework](http://fatfreeframework.com).

Use the issue tracker [in our code repository](https://github.com/inexor-game/code/issues) to report bugs or suggest improvments.

## Setup
We currently use nginx and php7.0-fpm for our production site.
To install in a production environment you should do the following:

1. Install nginx and php7.0-fpm
2. Clone the repository
3. Add below config

Following is a config for nginx and php7.0-fpm
```
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name some-domain.com;

    root /var/www/whereveryourgitis;
 
    location / {
        index index.php index.html index.htm;
        try_files $uri /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.0-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_index index.php;
        include fastcgi.conf;
    }


	location /.git {
		deny all;
	}
	
    location /config {
		deny all;
    }

    location /bin {
		deny all;
    }
}
```

### Automatically update the site
To set up automatic updates you can use composer and cron

1. Install composer locally in the inexor directory
2. Add a cronjob as below

```
@hourly sudo -u www-data -c "cd /var/www/inexor && git pull && git submodule update && bin/composer install"
```

