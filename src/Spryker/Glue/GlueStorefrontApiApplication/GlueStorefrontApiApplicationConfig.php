<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication;

use Spryker\Glue\GlueStorefrontApiApplication\Router\Generator\UrlGenerator;
use Spryker\Glue\GlueStorefrontApiApplication\Router\UrlMatcher\CompiledUrlMatcher;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\GlueStorefrontApiApplication\GlueStorefrontApiApplicationConstants;

class GlueStorefrontApiApplicationConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @uses \Spryker\Glue\GlueApplication\GlueApplicationConfig::ERROR_CODE_RESOURCE_NOT_FOUND
     *
     * @var string
     */
    public const ERROR_CODE_RESOURCE_NOT_FOUND = '007';

    /**
     * @api
     *
     * @uses \Spryker\Glue\GlueApplication\GlueApplicationConfig::ERROR_MESSAGE_RESOURCE_NOT_FOUND
     *
     * @var string
     */
    public const ERROR_MESSAGE_RESOURCE_NOT_FOUND = 'Not found';

    /**
     * @uses \Spryker\Zed\Oauth\OauthConfig::GENERATED_FULL_FILE_NAME
     *
     * @var string
     */
    protected const GENERATED_FULL_FILE_NAME = '/Generated/Zed/Oauth/GlueScopesCache/glue_scopes_cache.yml';

    /**
     * @var string
     */
    protected const HEADER_X_FRAME_OPTIONS_VALUE = 'SAMEORIGIN';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_SECURITY_POLICY_VALUE = 'frame-ancestors \'self\'';

    /**
     * @var string
     */
    protected const HEADER_X_CONTENT_TYPE_OPTIONS_VALUE = 'nosniff';

    /**
     * @var string
     */
    protected const HEADER_X_XSS_PROTECTION_VALUE = '1; mode=block';

    /**
     * @var string
     */
    protected const HEADER_REFERRER_POLICY_VALUE = 'same-origin';

    /**
     * @var string
     */
    protected const HEADER_PERMISSIONS_POLICY_VALUE = '';

    /**
     * @var string
     */
    protected const HEADER_ACCEPT = 'accept';

    /**
     * @var string
     */
    protected const HEADER_ACCEPT_LANGUAGE = 'accept-language';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_TYPE = 'content-type';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_LANGUAGE = 'content-language';

    /**
     * @var string
     */
    protected const HEADER_AUTHORIZATION = 'authorization';

    /**
     * Specification:
     * - Returns the host that the Storefront API application serves
     *
     * @api
     *
     * @return string
     */
    public function getStorefrontApiApplicationHost(): string
    {
        return $this->get(GlueStorefrontApiApplicationConstants::GLUE_STOREFRONT_API_HOST, '');
    }

    /**
     * Specification:
     * - Configures if api application should output debug statements
     *
     * @api
     *
     * @return bool
     */
    public function isDebugModeEnabled(): bool
    {
        return (bool)$this->get(
            GlueStorefrontApiApplicationConstants::ENABLE_APPLICATION_DEBUG,
            false,
        );
    }

    /**
     * Specification:
     * - Return the list of security headers returned with each request to the storefront API.
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getSecurityHeaders(): array
    {
        return [
            'X-Frame-Options' => static::HEADER_X_FRAME_OPTIONS_VALUE,
            'Content-Security-Policy' => static::HEADER_CONTENT_SECURITY_POLICY_VALUE,
            'X-Content-Type-Options' => static::HEADER_X_CONTENT_TYPE_OPTIONS_VALUE,
            'X-XSS-Protection' => static::HEADER_X_XSS_PROTECTION_VALUE,
            'Referrer-Policy' => static::HEADER_REFERRER_POLICY_VALUE,
            'Permissions-policy' => static::HEADER_PERMISSIONS_POLICY_VALUE,
        ];
    }

    /**
     * Specification:
     * - Returns a Router configuration which makes use of a Router cache.
     *
     * @api
     *
     * @see \Symfony\Component\Routing\Router::setOptions()
     *
     * @return array<string, mixed>
     */
    public function getRouterConfiguration(): array
    {
        return [
            'cache_dir' => $this->getCachePathIfCacheEnabled(),
            'generator_class' => UrlGenerator::class,
            'matcher_class' => CompiledUrlMatcher::class,
        ];
    }

    /**
     * Specification:
     * - List of allowed CORS headers.
     *
     * @api
     *
     * @return array<string>
     */
    public function getCorsAllowedHeaders(): array
    {
        return [
            static::HEADER_ACCEPT,
            static::HEADER_CONTENT_TYPE,
            static::HEADER_CONTENT_LANGUAGE,
            static::HEADER_ACCEPT_LANGUAGE,
            static::HEADER_AUTHORIZATION,
        ];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getGeneratedFullFileNameForCollectedScopes(): string
    {
        return APPLICATION_SOURCE_DIR . static::GENERATED_FULL_FILE_NAME;
    }

    /**
     * @return string|null
     */
    protected function getCachePathIfCacheEnabled(): ?string
    {
        if ($this->get(GlueStorefrontApiApplicationConstants::GLUE_IS_CACHE_ENABLED, true)) {
            return $this->get(GlueStorefrontApiApplicationConstants::GLUE_CACHE_PATH, $this->getDefaultRouterCachePath());
        }

        return null;
    }

    /**
     * @return string
     */
    protected function getDefaultRouterCachePath(): string
    {
        $projectNamespaces = implode('/', $this->get(GlueStorefrontApiApplicationConstants::PROJECT_NAMESPACES));

        return sprintf(
            '%s/src/Generated/%s/Router/codeBucket%s/%s',
            APPLICATION_ROOT_DIR,
            'Glue',
            APPLICATION_CODE_BUCKET,
            $projectNamespaces,
        );
    }
}
