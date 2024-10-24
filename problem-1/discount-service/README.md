## Create a microservice to calculate discounts

- I created a Symfony API (v5) and PHP 8.1 which receive orders by endpoint and return a JSON with the order processed with discounts applied.
- Applied hexagonal architecture (for future project growing) and DDD.
- Use of several VO as item, Money or Discount.

### Domain Layer structure

- Entity: Contain entities and aggregate roots needed for the project (Customer, Order).
- Service: Contain our service main logic and other needed services.
- ValueObject: Contain VO needed (Item, Money and Discount).
- Exception: Contain custom exceptions for error handling.
- Repository: Here are the contracts with repos.

### Application layer structure

- UseCase: Contain use cases folders.
- Contracts: The contract(iface) for Use Case.

### Infrastructure layer structure

- Controller: The api controller.
- Repository: The adapters for needed repositories.

### API Response
The API response is a json which contains an array of discounts.
I differentiate in discounts with amount (10%, 20%...) and discounts which giveaway items.
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
I serialized Discount, Item, Product and Money classes, so it's easy to add a new discount.<br>

### Discount Strategy
I created an interface "DiscountRuleInterface" and some rules will implement it according to Open/close SOLID principle. By this way if we need add another discount rule we only need to create a new rule inside Service/DiscountRules. After that we will have to modify "services.yaml" in order to register the new service and tag it as image shown:
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




