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
use Lakion\SyliusElasticSearchBundle\Search\Criteria\Filtering\ProductInChannelFilter;
use Lakion\SyliusElasticSearchBundle\Search\Criteria\Filtering\ProductInTaxonFilter;
use Lakion\SyliusElasticSearchBundle\Search\SearchEngineInterface;
use Sylius\Component\Core\Context\ShopperContextInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
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
     * @var TaxonRepositoryInterface
     */
    private $taxonRepository;

    /**
     * @var ShopperContextInterface
     */
    private $shopperContext;

    /**
     * @param ConfigurableViewHandlerInterface $restViewHandler
     * @param SearchEngineInterface $searchEngine
     * @param FormFactoryInterface $formFactory
     * @param TaxonRepositoryInterface $taxonRepository
     * @param ShopperContextInterface $shopperContext
     */
    public function __construct(
        ConfigurableViewHandlerInterface $restViewHandler,
        SearchEngineInterface $searchEngine,
        FormFactoryInterface $formFactory,
        TaxonRepositoryInterface $taxonRepository,
        ShopperContextInterface $shopperContext
    ) {
        $this->restViewHandler = $restViewHandler;
        $this->searchEngine = $searchEngine;
        $this->formFactory = $formFactory;
        $this->taxonRepository = $taxonRepository;
        $this->shopperContext = $shopperContext;
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

        $form = $this->formFactory->create(
            FilterSetType::class,
            Criteria::fromQueryParameters(
                $this->getResourceClassFromRequest($request),
                [
                    'per_page' => $request->get('per_page'),
                    new ProductInChannelFilter($this->shopperContext->getChannel()->getCode())
                ]
            ),
            ['filter_set' => $this->getFilterSetFromRequest($request)]
        );
        $form->handleRequest($request);

        /** @var Criteria $criteria */
        $criteria = $form->getData();

        $result = $this->searchEngine->match($criteria);
        $partialResult = $result->getResults($criteria->getPaginating()->getOffset(), $criteria->getPaginating()->getItemsPerPage());

        $view->setData([
            'products' => $partialResult->toArray(),
            'form' => $form->createView(),
            'criteria' => $criteria,
        ]);

        return $this->restViewHandler->handle($view);
    }

    /**
     * @param string $slug
     * @param Request $request
     *
     * @return Response
     */
    public function filterByTaxonAction($slug, Request $request)
    {
        $view = View::create();
        if ($this->isHtmlRequest($request)) {
            $view->setTemplate($this->getTemplateFromRequest($request));
        }
        $locale = $this->shopperContext->getLocaleCode();
        $taxon = $this->taxonRepository->findOneBySlug($slug, $locale);

        $form = $this->formFactory->create(
            FilterSetType::class,
            Criteria::fromQueryParameters(
                $this->getResourceClassFromRequest($request),
                ['per_page' => $request->get('per_page')]
            ),
            ['filter_set' => $taxon->getCode()]
        );
        $form->handleRequest($request);

        /** @var Criteria $criteria */
        $criteria = $form->getData();
        $criteria = Criteria::fromQueryParameters(
            $criteria->getResourceAlias(),
            array_merge($criteria->getFiltering()->getFields(), [
                    new ProductInTaxonFilter($taxon->getCode()),
                    new ProductInChannelFilter($this->shopperContext->getChannel()->getCode())
                ]
            )
        );

        $result = $this->searchEngine->match($criteria);
        $partialResult = $result->getResults($criteria->getPaginating()->getOffset(), $criteria->getPaginating()->getItemsPerPage());

        $view->setData([
            'products' => $partialResult->toArray(),
            'form' => $form->createView(),
            'criteria' => $criteria,
        ]);

        return $this->restViewHandler->handle($view);
    }

    /**
     * @param string $filterSetName
     *
     * @return Response
     */
    public function renderFilterSetAction(Request $request, $filterSetName)
    {
        $view = View::create();
        $view->setTemplate($this->getTemplateFromRequest($request));
        if (!$this->isHtmlRequest($request)) {
            throw new HttpException(Response::HTTP_BAD_REQUEST);
        }

        $form = $this->formFactory->create(
            FilterSetType::class,
            null,
            ['filter_set' => $filterSetName]
        );

        $view->setData([
            'form' => $form->createView(),
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
    private function getFilterSetFromRequest(Request $request)
    {
        $syliusAttributes = $request->attributes->get('_sylius');

        if (!isset($syliusAttributes['filter_set'])) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'You need to configure filter set in routing!');
        }

        return $syliusAttributes['filter_set'];
    }
}
