# 🍎🥕 Fruits and Vegetables
## Installation
First clone the repo
```bash 
git clone git@github.com:tadg-keating-webb/fruits-and-vegetables.git
```

Enter the directory
```bash
cd fruits-and-vegetables
```
Run composer install
```
composer install
```

Build the image with docker compose
```
docker compose up -d
```
Note: You may need to change the port numbers in the docker compose file if you experience clashes.

## 🎯 Goal
We want to build a service which will take a `request.json` and:
* Process the file and create two separate collections for `Fruits` and `Vegetables`
* Each collection has methods like `add()`, `remove()`, `list()`;
* Units have to be stored as grams;
* Store the collections in a storage engine of your choice. (e.g. Database, In-memory)
* Provide an API endpoint to query the collections. As a bonus, this endpoint can accept filters to be applied to the returning collection.
* Provide another API endpoint to add new items to the collections (i.e., your storage engine).
* As a bonus you might:
  * consider giving option to decide which units are returned (kilograms/grams);
  * how to implement `search()` method collections;
  * use latest version of Symfony's to embbed your logic 

## 🎯 Methodology
This solution could be considered over-engineering for such a simple tool, but it is built in such a way to make in highly scalable and focuses on data integrity,
It is built in such a way that we could switch out a storage class and it would make no effect on the rest of the process.
Likewise, we could switch out the collection logic and the API controller would still work unaffected as it only works with the collection service etc.
### Entities
* Parent produce entities + fruit and vegetable children
* Built with the single table option.
* This gives us the option to add fruit or vegetable specific fields in the future.
* Only used in the DB layer

### DTOs (Data Transfer Objects)
* Produce DTO - This handles the data we need to pass around the app about fruit or vegetables.
* Enforces data integrity.
* Built a fruit and vegetable specific DTOs, but no yet in use.

### Enum
* Produce Enum.
* Contains the values to use as the key for the collections.
* Fruit and Vegetable values, but more can be added as needed.

### Storage layer: 
* I built a storage interface.
* Database Storage class implements the interface
* The benefit is if we want to change to another storage option, eg. in-memory, we just need to switch out this one class and inject another into the collection classes.

### Collection layer:
* I built a collection interface with the methods: list, add, remove and search.
* Fruit and Veetable collections implement this interface and interact with the storage layer.
* Using an interface allows us to easily add other collections as we need while maintainin the app structure.

## Service layer.
* The collection service is designed to be injected into a controller or another service that needs to interact with the collections.
* It takes all the logic of decideing which collection to use away from the controller.

## API Layer
* Produce API controller.
* Used to add items to the collections.
* Used to list the items with filters.
* Use Postman for testin or visit - http://0.0.0.0/api/doc

# Improvements:
* Proper validation on the API controller, now we just check for values and generate a response, using Symfonys validation would reduce the amount of code and conditionals in the controller.


### ✔️ How can I check if my code is working?
* To test each piece of code, you can run the tests in the test/App directory.
```bash
docker compose exec app vendor/bin/phpunit
```
* To test the API, you can use postman, CURL or go to
```bash
http://0.0.0.0/api/doc
```

## 💡 Timeline
* Hour 1: Update symfony and install dependencies, set up storage layer
* Hour 2: Finished Storage layer & tests, started on collection interface and classes.
* Hour 3: Collections finished, built a service class to work with both collections.
* Hour 4: Added API controller and API docs, fleshed out Readme file. 


## 🐳 Docker 
Optional. The prefered way to run the app is to use docker compose, all related images included.

### 🧱 Building image
```bash
docker compose up -d
```
### 🛂 Running tests
```bash
docker compose exec app vendor/bin/phpunit
```

### ⌨️ Run development server
After building the docker image the web app will be available.
```bash
# Open http://0.0.0.0 in your browser or add a host file entry
```
