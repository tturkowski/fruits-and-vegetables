# Fruits and Vegetables

## Goal
We want to build a service which will take a `request.json` and:
* Process the file and create two separated collections for `Fruits` and `Vegetables`
* Every collection has methods like `add()`, `remove()`, `list()`;
* Units have to be stored as grams;
* As bonus you might consider to give an option to decide which units are returned (kilograms/grams);
* As bonus you might consider how to implement `search()` method collections;

## Building image
```bash
docker build -t fruits-and-vegetables -f docker/Dockerfile .
```

## Running container
```bash
docker run -it -w/app -v$(pwd):/app fruits-and-vegetables sh 
```

## Running tests
```bash
docker run -it -w/app -v$(pwd):/app fruits-and-vegetables bin/phpunit
```

## Run development server
```bash
docker run -it -w/app -v$(pwd):/app -p8080:8080 fruits-and-vegetables php -S 0.0.0.0:8080 -t /app/public
# Open http://127.0.0.1:8080 in your browser
```
