<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Criteria\Filtering;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class ProductInTaxonFilter
{
    /**
     * @var string
     */
    private $taxonCode;

    /**
     * @param string $taxonCode
     */
    public function __construct($taxonCode)
    {
        $this->taxonCode = $taxonCode;
    }

    /**
     * @return string
     */
    public function getTaxonCode()
    {
        return $this->taxonCode;
    }
}
