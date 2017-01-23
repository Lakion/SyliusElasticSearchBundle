<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Form\Configuration;

use Lakion\SyliusElasticSearchBundle\Form\Configuration\Filter;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class FilterSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedThrough('createFromConfiguration', [
            'color', ['type' => 'option', 'options' => ['code' => 'tshirt_color']]
        ]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Filter::class);
    }

    function it_has_name()
    {
        $this->getName()->shouldReturn('color');
    }

    function it_has_type()
    {
        $this->getType()->shouldReturn('option');
    }

    function it_has_options()
    {
        $this->getOptions()->shouldReturn([
            'code' => 'tshirt_color'
        ]);
    }

    function it_has_default_empty_options()
    {
        $this->beConstructedThrough('createFromConfiguration', [
            'color', ['type' => 'option']
        ]);

        $this->getOptions()->shouldReturn([]);
    }
}
