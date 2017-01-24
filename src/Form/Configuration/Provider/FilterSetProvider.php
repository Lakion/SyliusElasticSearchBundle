<?php

namespace Lakion\SyliusElasticSearchBundle\Form\Configuration\Provider;

use Lakion\SyliusElasticSearchBundle\Exception\FilterSetConfigurationNotFoundException;
use Zend\Stdlib\PriorityQueue;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class FilterSetProvider implements FilterSetProviderInterface
{
    /**
     * @var FilterSetProviderInterface[]
     */
    private $filterSetProviders;

    public function __construct()
    {
        $this->filterSetProviders = new PriorityQueue();
    }

    /**
     * @param FilterSetProviderInterface $filterSetProvider
     * @param int $priority
     */
    public function addFilterSetProvider(FilterSetProviderInterface $filterSetProvider, $priority = 1)
    {
        $this->filterSetProviders->insert($filterSetProvider, $priority);
    }

    /**
     * {@inheritdoc}
     */
    public function getFilterSetConfiguration($filterSetName)
    {
        foreach ($this->filterSetProviders as $filterSetProvider) {
            try {
                return $filterSetProvider->getFilterSetConfiguration($filterSetName);
            } catch (FilterSetConfigurationNotFoundException $configurationNotFoundException) {
                continue;
            }
        }

        throw new FilterSetConfigurationNotFoundException();
    }
}
