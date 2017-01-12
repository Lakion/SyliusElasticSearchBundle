<?php

namespace Lakion\SyliusElasticSearchBundle\Search\Criteria;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class Ordering
{
    const DEFAULT_FIELD = 'name';
    const DEFAULT_DIRECTION = self::ASCENDING_DIRECTION;
    const ASCENDING_DIRECTION = 'asc';
    const DESCENDING_DIRECTION = 'desc';

    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $direction;

    /**
     * @param string $field
     * @param string $direction
     */
    private function __construct($field, $direction)
    {
        $this->field = $field;
        $this->direction = $direction;
    }

    /**
     * @param array $parameters
     *
     * @return Ordering
     */
    public static function fromQueryParameters(array $parameters)
    {
        $field = isset($parameters['sort']) ? $parameters['sort'] : self::DEFAULT_FIELD;
        $direction = self::DEFAULT_DIRECTION;

        if ('-' === $field[0]) {
            $direction = self::DESCENDING_DIRECTION;
            $field = trim($field, '-');
        }

        return new self($field, $direction);
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }
}
