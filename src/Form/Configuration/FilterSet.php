<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\Form\Configuration;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class FilterSet
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Filter[]
     */
    private $filters;

    /**
     * @param string $name
     * @param Filter[] $filters
     */
    private function __construct($name, array $filters = [])
    {
        $this->name = $name;
        $this->filters = $filters;
    }

    /**
     * @param string $name
     * @param array $configuration
     *
     * @return FilterSet
     */
    public static function createFromConfiguration($name, array $configuration)
    {
        $filters = [];
        foreach ($configuration['filters'] as $filterName => $filterConfiguration)
        {
            $filters[] = Filter::createFromConfiguration($filterName, $filterConfiguration);
        }

        return new self($name, $filters);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Filter[]
     */
    public function getFilters()
    {
        return $this->filters;
    }
}
