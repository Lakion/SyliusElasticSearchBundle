<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Criteria;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class Criteria
{
    /**
     * @var string
     */
    private $resourceAlias;

    /**
     * @var Paginating
     */
    private $paginating;

    /**
     * @var Ordering
     */
    private $ordering;

    /**
     * @var Filtering
     */
    private $filtering;

    /**
     * @param string $resourceAlias
     * @param Paginating $paginating
     * @param Ordering $ordering
     * @param Filtering $filtering
     */
    private function __construct($resourceAlias, Paginating $paginating, Ordering $ordering, Filtering $filtering)
    {
        $this->resourceAlias = $resourceAlias;
        $this->paginating = $paginating;
        $this->ordering = $ordering;
        $this->filtering = $filtering;
    }

    /**
     * @param $resourceAlias
     * @param array $parameters
     *
     * @return Criteria
     */
    public static function fromQueryParameters($resourceAlias, array $parameters)
    {
        $paginating = Paginating::fromQueryParameters($parameters);
        $ordering = Ordering::fromQueryParameters($parameters);
        $filtering = Filtering::fromQueryParameters($parameters);

        return new self($resourceAlias, $paginating, $ordering, $filtering);
    }

    /**
     * @return string
     */
    public function getResourceAlias()
    {
        return $this->resourceAlias;
    }

    /**
     * @return Paginating
     */
    public function getPaginating()
    {
        return $this->paginating;
    }

    /**
     * @return Ordering
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * @return Filtering
     */
    public function getFiltering()
    {
        return $this->filtering;
    }
}
