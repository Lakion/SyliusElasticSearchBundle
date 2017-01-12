<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Exception;

use Lakion\SyliusElasticSearchBundle\Exception\MissingQueryParameterException;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class MissingQueryParameterExceptionSpec extends ObjectBehavior
{
    function let(\RuntimeException $previousException)
    {
        $this->beConstructedWith('parameter_key', 'class', $previousException);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(MissingQueryParameterException::class);
    }

    function it_is_runtime_exception()
    {
        $this->shouldHaveType(\RuntimeException::class);
    }

    function it_has_message()
    {
        $this->getMessage()->shouldReturn('Missing "parameter_key" parameter for "class" query.');
    }

    function it_has_previous_exception(\RuntimeException $previousException)
    {
        $this->getPrevious()->shouldReturn($previousException);
    }
}
