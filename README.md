# Fruits and Vegetables

## Goal
We want to build a service which will take a `request.json` and:
* Process the file and create two separate collections for `Fruits` and `Vegetables`
* Each collection has methods like `add()`, `remove()`, `list()`;
* Units have to be stored as grams;
* As a bonus you might consider giving option to decide which units are returned (kilograms/grams);
* As a bonus you might consider how to implement `search()` method collections;

## How can I check if my code is working?
You have two ways of moving on:
* You call the Service from PHPUnit test like it's done in dummy test (just run bin/phpunit from the console)

or

* You create a Controller which will be calling the service with a json payload

# If you want to use Docker (optional, no guarantee it will work on your system)

## Pulling image
```bash
docker pull tturkowski/fruits-and-vegetables
```

## Building image
```bash
docker build -t tturkowski/fruits-and-vegetables -f docker/Dockerfile .
```

## Running container
```bash
docker run -it -w/app -v$(pwd):/app tturkowski/fruits-and-vegetables sh 
```

## Running tests
```bash
docker run -it -w/app -v$(pwd):/app tturkowski/fruits-and-vegetables bin/phpunit
```

## Run development server
```bash
docker run -it -w/app -v$(pwd):/app -p8080:8080 tturkowski/fruits-and-vegetables php -S 0.0.0.0:8080 -t /app/public
# Open http://127.0.0.1:8080 in your browser
```
