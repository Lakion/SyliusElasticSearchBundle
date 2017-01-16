@searching_products
Feature: Filtering list of products by channels
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
    Scenario: Filtering products by mobile channel
        When I filter them by channel "Mobile"
        Then I should see 3 products on the list

    @domain
    Scenario: Filtering products by europe channel
        When I filter them by channel "Europe"
        Then I should see 2 products on the list

    @domain
    Scenario: Filtering products by tablets channel
        When I filter them by channel "Tablets"
        Then I should see 1 products on the list
