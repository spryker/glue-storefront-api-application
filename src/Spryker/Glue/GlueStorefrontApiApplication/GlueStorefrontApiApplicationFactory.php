<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication;

use Spryker\Glue\GlueStorefrontApiApplication\Application\GlueStorefrontApiApplication;
use Spryker\Glue\GlueStorefrontApiApplication\Cache\ControllerCacheCollector;
use Spryker\Glue\GlueStorefrontApiApplication\Cache\ControllerCacheCollectorInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Collector\StorefrontScopeCollector;
use Spryker\Glue\GlueStorefrontApiApplication\Collector\StorefrontScopeCollectorInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Dependency\Client\GlueStorefrontApiApplicationToStoreClientInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Dependency\External\GlueStorefrontApiApplicationToYamlAdapterInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Dependency\Service\GlueStorefrontApiApplicationToLocaleServiceInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Expander\ContextExpanderInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Expander\CustomRoutesContextExpander;
use Spryker\Glue\GlueStorefrontApiApplication\Expander\ResourcesContextExpander;
use Spryker\Glue\GlueStorefrontApiApplication\Finder\StorefrontScopeFinder;
use Spryker\Glue\GlueStorefrontApiApplication\Finder\StorefrontScopeFinderInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Language\LanguageNegotiation;
use Spryker\Glue\GlueStorefrontApiApplication\Language\LanguageNegotiationInterface;
use Spryker\Glue\GlueStorefrontApiApplication\RequestBuilder\LocaleRequestBuilder;
use Spryker\Glue\GlueStorefrontApiApplication\RequestBuilder\LocaleRequestBuilderInterface;
use Spryker\Glue\GlueStorefrontApiApplication\RequestValidator\RequestCorsValidator;
use Spryker\Glue\GlueStorefrontApiApplication\RequestValidator\RequestValidatorInterface;
use Spryker\Glue\GlueStorefrontApiApplication\RequestValidator\ScopeRequestAfterRoutingValidator;
use Spryker\Glue\GlueStorefrontApiApplication\ResourceRouteBuilder\ResourceRouteBuilder;
use Spryker\Glue\GlueStorefrontApiApplication\ResourceRouteBuilder\ResourceRouteBuilderInterface;
use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Application\ApplicationInterface;
use Spryker\Shared\Kernel\Container\ContainerProxy;
use Symfony\Component\Routing\RouteCollection;

/**
 * @method \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationConfig getConfig()
 */
class GlueStorefrontApiApplicationFactory extends AbstractFactory
{
    /**
     * @return array<\Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface>
     */
    public function getApplicationPlugins(): array
    {
        return $this->getProvidedDependency(GlueStorefrontApiApplicationDependencyProvider::PLUGINS_APPLICATIONS);
    }

    public function createGlueStorefrontApiApplication(): ApplicationInterface
    {
        static $applicationCache = null;

        return $applicationCache ?: $applicationCache = new GlueStorefrontApiApplication(
            $this->createServiceContainer(),
            $this->getApplicationPlugins(),
        );
    }

    public function createServiceContainer(): ContainerInterface
    {
        return new ContainerProxy(['logger' => null, 'debug' => $this->getConfig()->isDebugModeEnabled(), 'charset' => 'UTF-8']);
    }

    public function createLocaleRequestBuilder(): LocaleRequestBuilderInterface
    {
        return new LocaleRequestBuilder(
            $this->createLanguageNegotiation(),
        );
    }

    public function createLanguageNegotiation(): LanguageNegotiationInterface
    {
        return new LanguageNegotiation($this->getStoreClient(), $this->getLocaleService());
    }

    public function createControllerCacheCollector(): ControllerCacheCollectorInterface
    {
        return new ControllerCacheCollector(
            $this->getResourcePlugins(),
            $this->getRouteProviderPlugins(),
        );
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface>
     */
    public function getRouteProviderPlugins(): array
    {
        return $this->getProvidedDependency(GlueStorefrontApiApplicationDependencyProvider::PLUGINS_ROUTE_PROVIDER);
    }

    public function getStoreClient(): GlueStorefrontApiApplicationToStoreClientInterface
    {
        return $this->getProvidedDependency(GlueStorefrontApiApplicationDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface>
     */
    public function getResourcePlugins(): array
    {
        return $this->getProvidedDependency(GlueStorefrontApiApplicationDependencyProvider::PLUGINS_RESOURCE);
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestBuilderPluginInterface>
     */
    public function getRequestBuilderPlugins(): array
    {
        return $this->getProvidedDependency(GlueStorefrontApiApplicationDependencyProvider::PLUGINS_REQUEST_BUILDER);
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestValidatorPluginInterface>
     */
    public function getRequestValidatorPlugins(): array
    {
        return $this->getProvidedDependency(GlueStorefrontApiApplicationDependencyProvider::PLUGINS_REQUEST_VALIDATOR);
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RequestAfterRoutingValidatorPluginInterface>
     */
    public function getRequestAfterRoutingValidatorPlugins(): array
    {
        return $this->getProvidedDependency(GlueStorefrontApiApplicationDependencyProvider::PLUGINS_REQUEST_AFTER_ROUTING_VALIDATOR);
    }

    /**
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResponseFormatterPluginInterface>
     */
    public function getResponseFormatterPlugins(): array
    {
        return $this->getProvidedDependency(GlueStorefrontApiApplicationDependencyProvider::PLUGINS_RESPONSE_FORMATTER);
    }

    public function createRequestCorsValidator(): RequestValidatorInterface
    {
        return new RequestCorsValidator($this->getConfig());
    }

    public function createStorefrontScopeCollector(): StorefrontScopeCollectorInterface
    {
        return new StorefrontScopeCollector(
            $this->getResourcePlugins(),
            $this->getRouteProviderPlugins(),
            $this->createRouteCollection(),
        );
    }

    public function getYamlAdapter(): GlueStorefrontApiApplicationToYamlAdapterInterface
    {
        return $this->getProvidedDependency(GlueStorefrontApiApplicationDependencyProvider::ADAPTER_YAML);
    }

    public function createStorefrontScopeFinder(): StorefrontScopeFinderInterface
    {
        return new StorefrontScopeFinder(
            $this->getConfig(),
            $this->getYamlAdapter(),
        );
    }

    public function createScopeRequestAfterRoutingValidator(): RequestValidatorInterface
    {
        return new ScopeRequestAfterRoutingValidator();
    }

    public function createResourcesContextExpander(): ContextExpanderInterface
    {
        return new ResourcesContextExpander($this->getResourcePlugins());
    }

    public function createCustomRoutesContextExpander(): ContextExpanderInterface
    {
        return new CustomRoutesContextExpander($this->getRouteProviderPlugins());
    }

    public function createRouteCollection(): RouteCollection
    {
        return new RouteCollection();
    }

    public function createResourceRouteBuilder(): ResourceRouteBuilderInterface
    {
        return new ResourceRouteBuilder();
    }

    public function getLocaleService(): GlueStorefrontApiApplicationToLocaleServiceInterface
    {
        return $this->getProvidedDependency(GlueStorefrontApiApplicationDependencyProvider::SERVICE_LOCALE);
    }
}
