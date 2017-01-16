@searching_products
Feature: Filtering list of products by channels and price
    In order to see more specific list of the products
    As an Visitor
    I want to be able to filter the products

    Background:
        Given the store operates on a channel named "Europe"
        And the store also operates on another channel named "Mobile"
        And the store also operates on another channel named "Tablets"
        And the store has a product "Banana T-Shirt" priced at "$100" in "Tablets" channel
        And the store also has a product "Star Wars T-Shirt" priced at "$150" in "Mobile" channel
        And the store also has a product "LOTR T-Shirt" priced at "$300" in "Mobile" channel
        And the store also has a product "Breaking Bad T-Shirt" priced at "$50" in "Mobile" channel
        And the store also has a product "Westworld T-Shirt" priced at "$1000" in "Europe" channel
        And the store also has a product "Orange T-Shirt" priced at "$1000" in "Europe" channel

    @domain
    Scenario: Filtering products by mobile channel and price range
        When I filter them by channel "Mobile" and price between "$50" and "$200"
        Then I should see 2 products on the list

    @domain
    Scenario: Filtering products by mobile channel and price range
        When I filter them by channel "Mobile" and price between "$50" and "$300"
        Then I should see 3 products on the list

    @domain
    Scenario: Filtering products by europe channel and price range
        When I filter them by channel "Europe" and price between "$100" and "$200"
        Then I should see 0 products on the list

    @domain
    Scenario: Filtering products by europe channel and price range
        When I filter them by channel "Europe" and price between "$1000" and "$2000"
        Then I should see 2 products on the list

    @domain
    Scenario: Filtering products by tablets channel and price range
        When I filter them by channel "Tablets" and price between "$100" and "$200"
        Then I should see 1 products on the list
