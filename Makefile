build:
	docker-compose up --build --detach

destroy:
	docker-compose down --volumes

start:
	docker-compose up --detach

logs:
	docker-compose logs --follow app

stop:
	docker-compose stop

shell:
	docker-compose exec app bash

clean:
	docker system prune -f && docker volume prune -f

