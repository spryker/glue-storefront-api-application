<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication\Plugin\Oauth;

use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\OauthExtension\Dependency\Plugin\ScopeCollectorPluginInterface;

/**
 * @method \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationFactory getFactory()
 */
class StorefrontScopeCollectorPlugin extends AbstractPlugin implements ScopeCollectorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Provides the set of OAuth scopes for storefront.
     *
     * @api
     *
     * @see {@link https://datatracker.ietf.org/doc/html/rfc6749#section-3.3}
     *
     * @return array<\Generated\Shared\Transfer\OauthScopeFindTransfer>
     */
    public function provideScopes(): array
    {
        return $this->getFactory()->createStorefrontScopeCollector()->collect();
    }
}
