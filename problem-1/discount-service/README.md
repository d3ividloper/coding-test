# Create a microservice to calculate discounts
I created a Symfony API (v5) and PHP 8.1 which receive orders by endpoint and return a JSON with the order processed with discounts applied. 
Applying hexagonal architecture (for future project growing) and DDD. I added some tests and the use case for our microservice.

## Table of Contents
* [Usage](#how-to-test)
* [Layers structure](#layers-structure)
* [Tests](#tests)
* [API](#api)



## How to Test
As a Symfony Project, you can test it by using symfony server and Postman to make requests.

1. Clone the repo
2. Open a console and go to folder `problem-1/discount-service`
2. Run `composer install` command in the console.
3. Run `symfony serve` command in the console. The server listen por 8000 (http://localhost:8000)
4. Open Postman and make a **POST** request `http://localhost:800/discount` <br/> You can use [example-orders](../../example-orders/) folder data as JSON body.


## Layers Structure
### 1. Domain Layer structure

- Entity: Contain entities and aggregate roots needed for the project (Customer, Order).
- Service: Contain our service main logic and other needed services.
- ValueObject: Contain VO needed (Item, Money and Discount).
- Exception: Contain custom exceptions for error handling.
- Repository: Here are the contracts with repos.

### 2. Application layer structure

- UseCase: Contain use cases folders.
- Contracts: The contract(iface) for Use Case.

### 3. Infrastructure layer structure

- Controller: The api controller.
- Repository: The adapters for needed repositories.

### TESTS
- I added some unit [tests](./tests) using PHPUnit. I added the Use case, an entity and some services in order to see the different tests we can do.

## API
### Response
The API response is in json format which contains an array of discounts.
I differentiate in both cases: discounts with amount (10%, 20%...) and discounts which giveaway items.
As you can see in the code below:
```
{
    "order_discounts": [
        {
            "amount": {
                "amount": 9.4,
                "currency": "EURO"
            },
            "description": "Customer revenue over 1000€ -> Got 10% discount from order total amount.",
            "freeItems": []
        },
        {
            "amount": null,
            "description": "Bought 5 Switches -> Got 6th switch for free.",
            "freeItems": {
                "category": "Switch",
                "freeQuantity": 1
            }
        },
        {
            "amount": {
                "amount": 1.95,
                "currency": "EURO"
            },
            "description": "Bought 2 or more Tools -> Got 20% discount over cheapest item.",
            "freeItems": []
        }
    ]
}
```
`Discount` has been created as **VO** in order to encapsulate the logic and allow us to create as Discounts as we need in the future.

### <p name=discount> Discount Strategy</p>
I created an interface [DiscountRuleInterface](./src/Domain/Service/DiscountRuleInterface.php) and some [rules](./src/Domain/Service/DiscountRules) will implement it according to Open/close SOLID principle. 
By this way if we need add another discount rule we only need to create a new rule inside [Service/DiscountRules](./src/Domain/Service/DiscountRules). After that we will have to modify [services.yaml](./config/services.yaml) in order to register the new service and tag it as image shown:
```
    # Register all DiscountRules and DiscountCalculator
    App\Domain\Service\DiscountRules\OrderOver1000DiscountRule:
        tags: ['discount.rule']

    App\Domain\Service\DiscountRules\SwitchesCategoryDiscountRule:
        tags: ['discount.rule']

    App\Domain\Service\DiscountRules\ToolsCategoryDiscountRule:
        tags: [ 'discount.rule' ]

    App\Domain\Service\DiscountCalculator:
        arguments:
            $discountRules: !tagged discount.rule
```



