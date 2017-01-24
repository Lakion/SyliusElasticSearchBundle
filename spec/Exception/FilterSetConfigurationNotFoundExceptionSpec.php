<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Exception;

use Lakion\SyliusElasticSearchBundle\Exception\FilterSetConfigurationNotFoundException;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class FilterSetConfigurationNotFoundExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(FilterSetConfigurationNotFoundException::class);
    }

    function it_is_runtime_exception()
    {
        $this->shouldHaveType(\RuntimeException::class);
    }

    function it_has_default_message()
    {
        $this->getMessage()->shouldReturn('Filter set configuration not found.');
    }

    function it_has_custom_message()
    {
        $this->beConstructedWith('Some custom message.');

        $this->getMessage()->shouldReturn('Some custom message.');
    }

    function it_has_previous_exception()
    {
        $previousException =  new \InvalidArgumentException();
        $this->beConstructedWith('Some custom message.', $previousException);

        $this->getPrevious()->shouldReturn($previousException);
    }
}
