# wp-raudio

Webradio player for Wordpress

## Development

Download [Docker CE](https://www.docker.com/get-docker) for your OS.

### Dev Server

Point terminal to your project root and start up the container.

```shell
docker-compose up -d
```

Open http://localhost:8030 and go through Wordpress installation.
Go to plugins page and enable Raudio plugin.

You find phpMyAdmin at http://localhost:8031

### Update composer dependencies

```shell
docker-compose run composer update
```

### Useful docker commands

Globally stop all running docker containers.

```shell
docker stop $(docker ps -a -q)
```
