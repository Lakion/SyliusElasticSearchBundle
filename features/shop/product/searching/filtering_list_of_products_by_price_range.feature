@searching_products
Feature: Filtering list of products by price range
    In order to see more specific list of the products
    As an Visitor
    I want to be able to filter the products

    Background:
        Given the store operates on a single channel in "United States"
        And the store has a product "Banana t-shirt" priced at "$100"
        And the store also has a product "Star wars t-shirt" priced at "$150"
        And the store also has a product "LOTR t-shirt" priced at "$300"
        And the store also has a product "Breaking bad t-shirt" priced at "$50"

    @domain
    Scenario: Filtering products by price range
        When I filter them by price between "$100" and "$200"
        Then I should see 2 products on the list
