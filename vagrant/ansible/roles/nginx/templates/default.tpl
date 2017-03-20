
server {
    listen 80;
    server_name timberland.app;
    root        {{ doc_root }};

    error_log   /var/log/nginx/timberland_error.log;
    access_log  /var/log/nginx/timberland_access.log;

    rewrite     ^/(index|index_dev)\.php/?(.*)$ /$1 permanent;

    location / {
        index       index_dev.php;
        try_files   $uri @rewriteapp;
    }

    location @rewriteapp {
        rewrite     ^(.*)$ /index_dev.php/$1 last;
    }

    location ~ ^/(index|index_dev|config)\.php(/|$) {
        fastcgi_pass            unix:/var/run/php5-fpm.sock;
        fastcgi_buffer_size     16k;
        fastcgi_buffers         4 16k;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include                 fastcgi_params;
        fastcgi_param           SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param           HTTPS           off;
    }
}