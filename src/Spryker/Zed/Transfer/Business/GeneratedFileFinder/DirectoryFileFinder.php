<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\GeneratedFileFinder;

use Symfony\Component\Finder\Finder;

class DirectoryFileFinder implements GeneratedFileFinderInterface
{
    /**
     * @var \Symfony\Component\Finder\Finder
     */
    protected $finder;

    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
    }

    public function findFiles(string $directoryPath): Finder
    {
        $finder = clone $this->finder;
        $finder
            ->in($directoryPath)
            ->depth(0);

        return $finder;
    }
}
