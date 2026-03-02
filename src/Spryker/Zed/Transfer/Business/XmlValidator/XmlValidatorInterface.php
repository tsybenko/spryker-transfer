<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\XmlValidator;

interface XmlValidatorInterface
{
    public function validate(string $file, string $schema): void;

    public function isValid(): bool;

    /**
     * @return array<string>
     */
    public function getErrors(): array;
}
