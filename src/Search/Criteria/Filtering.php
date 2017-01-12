<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Criteria;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class Filtering
{
    /**
     * @var array
     */
    private $fields;

    /**
     * @param array $fields
     */
    private function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @param array $queryParameters
     *
     * @return Filtering
     */
    public static function fromQueryParameters(array $queryParameters)
    {
        $fields = $queryParameters;

        unset($fields['page']);
        unset($fields['per_page']);
        unset($fields['sort']);

        return new self($fields);
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }
}
