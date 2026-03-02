<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

/**
 * @method string getName()
 */
interface ClassDefinitionInterface extends DefinitionInterface
{
    public function getConstants(): array;

    public function getProperties(): array;

    /**
     * @return array<string, string>
     */
    public function getPropertyNameMap(): array;

    public function getConstructorDefinition(): array;

    public function getMethods(): array;

    public function getNormalizedProperties(): array;

    public function getDeprecationDescription(): ?string;

    /**
     * @return array<string>
     */
    public function getUseStatements(): array;

    public function getEntityNamespace(): ?string;

    public function isDebugMode(): bool;
}
