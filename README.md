Sylius ElasticSearchBundle
==========================
[![Build status on Linux](https://img.shields.io/travis/Lakion/SyliusElasticSearchBundle/master.svg)](http://travis-ci.org/Lakion/SyliusELasticSearchBundle)
Elastic search for Sylius.

## Usage

1. Install it:

    ```bash
    $ composer require lakion/sylius-elastic-search-bundle
    ```
2. Install elastic search server:

    ```bash
    $ brew install elasticsearch@1.7
    ```

3. Run elastic search server:

    ```bash
    $ elasticsearch
    ```

4. Populate your elastic search server with command or your custom code:

    ```bash
    $ app/console fos:elastic:pop
    ```

5. Add this bundle to `AppKernel.php`:

    ```php
    new \Lakion\SyliusElasticSearchBundle\LakionSyliusElasticSearchBundle(),
    ```

6. Import config file in `app/config/config.yml`:

    ```yaml
    imports:
       - { resource: "@LakionSyliusElasticSearchBundle/Resources/config/app/config.yml" }
    ```

7. Import routing files in `app/config/routing.yml`:

    ```yaml
    sylius_search:
        resource: "@LakionSyliusElasticSearchBundle/Resources/config/routing.yml"
    ```

8. Configure:

    ```yaml
    lakion_sylius_elastic_search:
        filter_sets:
            mug_type_and_stickers_set:
                filters:
                    product_options:
                        type: options
                        options:
                            codes: [mug_type, sticker_size]
                            filtering_key: product_option_code
                    product_price:
                        type: price
                        options:
                            filtering_key: product_price_range
    ```
