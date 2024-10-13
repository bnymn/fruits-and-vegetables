# 1. Technical implementation details
In this section, we are going to write down the technical implementation details.

We are going to use the clean architecture implementation style.
1. **src/Domain**: It contains business entities
2. **src/Application**: It contains business use cases
3. **src/Infrastructure**: It contains framework connections

## 1.1. Create entities
The task definition asks us to create two separate collections. I assume they are two different entities for the sake
of the simplicity of the assignment, even though they look similar at first. For this reason, we are going to create
two different entities with no parent and children relation in between.

### 1.1.1. Create a Fruit entity
Fruit entity will have the following attributes:
- id
- name
- quantity

### 1.1.2. Create a Vegetable entity
Vegetable entity will have the following attributes:
- id
- name
- quantity

## 1.2. Create repositories
We are going to use in-memory database. So, there is no need to install doctrine packages.

### 1.2.1. Create a FruitRepository
FruitRepository will have the following methods:
- save
- deleteById
- findAll

### 1.2.2. Create a VegetableRepository
VegetableRepository will have the following methods:
- save
- deleteById
- findAll

## 1.3. Create data import logic
Each data import should have the following steps:
- Reading
- Validation
- Transformation
- Writing

We are going to create these steps in an **abstract** data import class. The data importers of certain entities can
implement these steps according to the business requirements.

### 1.3.1. Create combined data import for fruits and vegetables
The `request.json` file has fruits and vegetables combined inside even though they are completely different entities.
This is a common practice for product imports, because product managers can manipulate a single file easier compared
to editing multiple files.

We are going to create a `CombinedDataImporter` which will have its own `Transformer`, `Validator` and `Writer`
implementations. The combined writer implementation should use individual writer implementations of fruit and vegetable.

- `Transformer` class should convert kg to g.
- `Validator` class should validate the followings:
  - `id` is numeric.
  - `name` is not empty.
  - `quantity` is numeric.
  - `type` is either `fruit` or `vegetable`.

## 1.4. Create API endpoints
We are going to create four API endpoints:
- GET /vegetables/
  - It will return all vegetables in JSON format.
- GET /fruits/
  - It will return all fruits in JSON format.
- POST /vegetables/
  - It will create a new vegetable.
- POST /fruits/
  - It will create a new fruit.

# FAQ

## How to run phpynit
```shell
XDEBUG_MODE=coverage php -d memory_limit=512M bin/phpunit
```

## How to run phpstan
```shell
docker run --rm -v .:/app ghcr.io/phpstan/phpstan:1-php8.3
```

## How to run phpstan

# 2. Goals
- We want to build a service which will take a request.json and:
- ✅ Process the file and create two separate collections for Fruits and Vegetables
- ✅ Each collection has methods like add(), remove(), list();
- ✅ Units have to be stored as grams;
- ✅ Store the collections in a storage engine of your choice. (e.g. Database, In-memory)
- ✅ Provide an API endpoint to query the collections.
- ⏳ As a bonus, this endpoint can accept filters to be applied to the returning collection.
- ✅ Provide another API endpoint to add new items to the collections (i.e., your storage engine).
- As a bonus you might:
- ⏳ consider giving option to decide which units are returned (kilograms/grams);
- ⏳ how to implement search() method collections;
- ✅ use latest version of Symfony's to embbed your logic
