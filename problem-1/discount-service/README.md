## Create a microservice to calculate discounts

- I created a Symfony API which receive orders by endpoint and return a JSON with the order processed with discounts applied.
- Applied hexagonal architecture (for future project growing) and DDD.

### Domain Layer structure

- Entity: Contain entities and aggregate roots needed for the project (Customer, Order).
- Service: Contain our service main logic and other needed services.
- ValueObject: Contain VO needed (Item, Money and Discount).

### Application layer structure

- UseCase: Contain use cases folders.

### Infrastructure layer structure

- 
