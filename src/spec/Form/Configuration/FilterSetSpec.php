<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Form\Configuration;

use Lakion\SyliusElasticSearchBundle\Form\Configuration\Filter;
use Lakion\SyliusElasticSearchBundle\Form\Configuration\FilterSet;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class FilterSetSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedThrough('createFromConfiguration', [
            't_shirts', [
                'filters' => [
                    'color' => [
                        'type' => 'option',
                        'options' => [
                            'code' => 'tshirt_color'
                        ]
                    ],
                    'size' => [
                        'type' => 'option',
                        'options' => [
                            'code' => 'tshirt_size'
                        ]
                    ]
                ]
            ]
        ]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FilterSet::class);
    }

    function it_has_name()
    {
        $this->getName()->shouldReturn('t_shirts');
    }

    function it_has_filters()
    {
        $this->getFilters()->shouldBeLike([
            Filter::createFromConfiguration('color', ['type' => 'option', 'options' => ['code' => 'tshirt_color']]),
            Filter::createFromConfiguration('size', ['type' => 'option', 'options' => ['code' => 'tshirt_size']])
        ]);
    }
}
