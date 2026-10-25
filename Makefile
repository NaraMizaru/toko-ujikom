#!make
include .env

IMAGE_VERSION ?= latest

export IMAGE_VERSION
export DOCKER_REGISTRY

ps:
	docker compose -f docker-compose.yaml -p ${DOCKER_NAME} ps;

serve:
	docker compose -f docker-compose.yaml -p ${DOCKER_NAME} up -d;

serve-with-util:
	docker compose -f docker-compose.yaml -f docker-compose.util.yaml -p ${DOCKER_NAME} up -d;

down:
	docker compose -f docker-compose.yaml -p ${DOCKER_NAME} down;

shell:
	docker compose -f docker-compose.yaml -p ${DOCKER_NAME} exec -it web sh;
