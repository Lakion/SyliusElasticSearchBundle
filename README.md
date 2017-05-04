Sylius ElasticSearchBundle
==========================
Elastic search for Sylius.
[![Build status on Linux](https://img.shields.io/travis/Lakion/SyliusElasticSearchBundle/master.svg)](http://travis-ci.org/Lakion/SyliusELasticSearchBundle)

## Usage

1. Install it:

    ```bash
    $ composer require lakion/sylius-elastic-search-bundle
    ```
2. Install elastic search server:

    ```bash
    $ brew install elasticsearch@2.4
    ```

3. Run elastic search server:

    ```bash
    $ elasticsearch
    ```

4. Add this bundle to `AppKernel.php`:

    ```php
    new \FOS\ElasticaBundle\FOSElasticaBundle(),
    new \Lakion\SyliusElasticSearchBundle\LakionSyliusElasticSearchBundle(),
    ```

5. Create/Setup database:

    ```bash
    $ app/console do:da:cr
    $ app/console do:sch:cr
    $ app/console syl:fix:lo
    ```

6. Populate your elastic search server with command or your custom code:

    ```bash
    $ app/console fos:elastic:pop
    ```

7. Import config file in `app/config/config.yml` for default filter set configuration:

    ```yaml
    imports:
       - { resource: "@LakionSyliusElasticSearchBundle/Resources/config/app/config.yml" }
    ```

8. Import routing files in `app/config/routing.yml`:

    ```yaml
    sylius_search:
        resource: "@LakionSyliusElasticSearchBundle/Resources/config/routing.yml"
    ```

8. Configuration reference:

    ```yaml
    lakion_sylius_elastic_search:
        filter_sets:
            mugs:
                filters:
                    product_options:
                        type: option
                        options:
                            code: mug_type
                    product_price:
                        type: price
    ```
