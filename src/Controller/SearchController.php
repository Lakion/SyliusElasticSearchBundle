<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lakion\SyliusElasticSearchBundle\Controller;

use FOS\RestBundle\View\ConfigurableViewHandlerInterface;
use FOS\RestBundle\View\View;
use Lakion\SyliusElasticSearchBundle\Form\Type\FilterSetType;
use Lakion\SyliusElasticSearchBundle\Search\Criteria\Criteria;
use Lakion\SyliusElasticSearchBundle\Search\SearchEngineInterface;
use Sylius\Component\Core\Context\ShopperContextInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class SearchController
{
    /**
     * @var ConfigurableViewHandlerInterface
     */
    private $restViewHandler;

    /**
     * @var SearchEngineInterface
     */
    private $searchEngine;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var ShopperContextInterface
     */
    private $shopperContext;

    /**
     * @param ConfigurableViewHandlerInterface $restViewHandler
     * @param SearchEngineInterface $searchEngine
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        ConfigurableViewHandlerInterface $restViewHandler,
        SearchEngineInterface $searchEngine,
        FormFactoryInterface $formFactory
    ) {
        $this->restViewHandler = $restViewHandler;
        $this->searchEngine = $searchEngine;
        $this->formFactory = $formFactory;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function filterAction(Request $request)
    {
        $view = View::create();
        if ($this->isHtmlRequest($request)) {
            $view->setTemplate($this->getTemplateFromRequest($request));
        }

        $form = $this->formFactory->create(FilterSetType::class, Criteria::fromQueryParameters($this->getResourceClassFromRequest($request), []), ['filter_set' => $this->getFilterScopeFromRequest($request)]);
        $form->handleRequest($request);
        $criteria = $form->getData();
        $criteria = Criteria::fromQueryParameters($this->getResourceClassFromRequest($request), array_merge($request->query->all(), $request->attributes->all(), $criteria->getFiltering()->getFields()));

        $result = $this->searchEngine->match($criteria);
        $partialResult = $result->getResults($criteria->getPaginating()->getOffset(), $criteria->getPaginating()->getItemsPerPage());

        $view->setData([
            'resources' => $partialResult->toArray(),
            'form' => $form->createView(),
            'criteria' => $criteria,
        ]);

        return $this->restViewHandler->handle($view);
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    private function getTemplateFromRequest(Request $request)
    {
        $syliusAttributes = $request->attributes->get('_sylius');

        if (!isset($syliusAttributes['template'])) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'You need to configure template in routing!');
        }

        return $syliusAttributes['template'];
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    private function getResourceClassFromRequest(Request $request)
    {
        $syliusAttributes = $request->attributes->get('_sylius');

        if (!isset($syliusAttributes['resource_class'])) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'You need to configure resource class in routing!');
        }

        return $syliusAttributes['resource_class'];
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    private function isHtmlRequest(Request $request)
    {
        return !$request->isXmlHttpRequest() && 'html' === $request->getRequestFormat();
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    private function getFilterScopeFromRequest(Request $request)
    {
        $syliusAttributes = $request->attributes->get('_sylius');

        if (!isset($syliusAttributes['filter_set'])) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'You need to configure filter set in routing!');
        }

        return $syliusAttributes['filter_set'];
    }
}
