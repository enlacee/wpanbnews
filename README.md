### Plugin Cron with wordpress

**worpress** - **twig theme** - **plugin**


## Ejecutar cron

Para agregar noticias con el cron usar:

	http://local.ndperu.net/wp-cron.php

## Dependencias

	sudo apt-get install php5-curl

## Agregar la tarea en el servidor

Abrir y editar el crontab ver mas sobre [crontab](http://kvz.io/blog/2007/07/29/schedule-tasks-on-linux-using-crontab/)

>crontab -e


Agregar y ejecutar la tarea cada 15 minutos

>15 * * * * wget -q -O - http://www.noticiasdelperu.net/wp-cron.php?anbnews_cron_read_feed

## Preview
![preview](README/screenshot.png)
