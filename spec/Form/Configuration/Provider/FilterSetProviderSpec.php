<?php

namespace spec\Lakion\SyliusElasticSearchBundle\Form\Configuration\Provider;

use Lakion\SyliusElasticSearchBundle\Exception\FilterSetConfigurationNotFoundException;
use Lakion\SyliusElasticSearchBundle\Form\Configuration\FilterSet;
use Lakion\SyliusElasticSearchBundle\Form\Configuration\Provider\FilterSetProvider;
use Lakion\SyliusElasticSearchBundle\Form\Configuration\Provider\FilterSetProviderInterface;
use PhpSpec\ObjectBehavior;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class FilterSetProviderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(FilterSetProvider::class);
    }

    function it_is_filter_set_configuration_provider()
    {
        $this->shouldImplement(FilterSetProviderInterface::class);
    }

    function it_returns_filter_set_configuration_from_another_filter_set_provider(
        FilterSetProviderInterface $dataBaseFilterSetProvider
    ) {
        $filterSetConfiguration = FilterSet::createFromConfiguration('default', ['filters' => []]);
        $this->addFilterSetProvider($dataBaseFilterSetProvider);
        $dataBaseFilterSetProvider->getFilterSetConfiguration('default')
            ->willReturn($filterSetConfiguration)
        ;

        $this->getFilterSetConfiguration('default')->shouldReturn($filterSetConfiguration);
    }

    function it_returns_filter_set_configuration_from_first_available(
        FilterSetProviderInterface $dataBaseFilterSetProvider,
        FilterSetProviderInterface $fromConfigurationFilterSetProvider
    ) {
        $filterSetConfiguration = FilterSet::createFromConfiguration('default', ['filters' => []]);
        $this->addFilterSetProvider($dataBaseFilterSetProvider);
        $this->addFilterSetProvider($fromConfigurationFilterSetProvider);

        $dataBaseFilterSetProvider->getFilterSetConfiguration('default')
            ->willReturn($filterSetConfiguration)
        ;

        $fromConfigurationFilterSetProvider->getFilterSetConfiguration('default')->shouldNotBeCalled();

        $this->getFilterSetConfiguration('default')->shouldReturn($filterSetConfiguration);
    }

    function it_returns_filter_set_configuration_based_on_priority(
        FilterSetProviderInterface $dataBaseFilterSetProvider,
        FilterSetProviderInterface $fromConfigurationFilterSetProvider,
        FilterSetProviderInterface $fromApiFilterSetProvider
    ) {
        $filterSetConfiguration = FilterSet::createFromConfiguration('default', ['filters' => []]);
        $this->addFilterSetProvider($dataBaseFilterSetProvider, 1);
        $this->addFilterSetProvider($fromConfigurationFilterSetProvider, 2);
        $this->addFilterSetProvider($fromApiFilterSetProvider, 10);

        $fromApiFilterSetProvider->getFilterSetConfiguration('default')->willReturn($filterSetConfiguration);
        $fromConfigurationFilterSetProvider->getFilterSetConfiguration('default')->shouldNotBeCalled();
        $dataBaseFilterSetProvider->getFilterSetConfiguration('default')->shouldNotBeCalled();

        $this->getFilterSetConfiguration('default')->shouldReturn($filterSetConfiguration);
    }

    function it_returns_filter_set_configuration_from_next_one_when_first_throws_configuration_not_found_exception(
        FilterSetProviderInterface $dataBaseFilterSetProvider,
        FilterSetProviderInterface $fromConfigurationFilterSetProvider,
        FilterSetProviderInterface $fromApiFilterSetProvider
    ) {
        $filterSetConfiguration = FilterSet::createFromConfiguration('default', ['filters' => []]);
        $this->addFilterSetProvider($dataBaseFilterSetProvider, 1);
        $this->addFilterSetProvider($fromConfigurationFilterSetProvider, 2);
        $this->addFilterSetProvider($fromApiFilterSetProvider, 10);

        $fromApiFilterSetProvider->getFilterSetConfiguration('default')->willThrow(FilterSetConfigurationNotFoundException::class);
        $fromConfigurationFilterSetProvider->getFilterSetConfiguration('default')->willReturn($filterSetConfiguration);
        $dataBaseFilterSetProvider->getFilterSetConfiguration('default')->shouldNotBeCalled();

        $this->getFilterSetConfiguration('default')->shouldReturn($filterSetConfiguration);
    }

    function it_throws_filter_set_configuration_not_found_exception_if_cannot_get_configuration()
    {
        $this->shouldThrow(FilterSetConfigurationNotFoundException::class)->during('getFilterSetConfiguration', ['default']);
    }

    function it_throws_filter_set_configuration_not_found_exception_if_none_of_provider_returns_configuration(
        FilterSetProviderInterface $dataBaseFilterSetProvider,
        FilterSetProviderInterface $fromConfigurationFilterSetProvider,
        FilterSetProviderInterface $fromApiFilterSetProvider
    ) {
        $this->addFilterSetProvider($dataBaseFilterSetProvider, 1);
        $this->addFilterSetProvider($fromConfigurationFilterSetProvider, 2);
        $this->addFilterSetProvider($fromApiFilterSetProvider, 10);

        $fromApiFilterSetProvider->getFilterSetConfiguration('default')->willThrow(FilterSetConfigurationNotFoundException::class);
        $fromConfigurationFilterSetProvider->getFilterSetConfiguration('default')->willThrow(FilterSetConfigurationNotFoundException::class);
        $dataBaseFilterSetProvider->getFilterSetConfiguration('default')->willThrow(FilterSetConfigurationNotFoundException::class);

        $this->shouldThrow(FilterSetConfigurationNotFoundException::class)->during('getFilterSetConfiguration', ['default']);
    }
}
