<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\GeneratedFileFinder;

use ReflectionClass;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

abstract class AbstractTransferFileFinder implements GeneratedFileFinderInterface
{
    /**
     * @var string
     */
    protected const TRANSFER_NAMESPACE_PATTERN = 'Generated\Shared\Transfer\%s';

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
        $finder->in($directoryPath)
            ->depth(0)
            ->filter(function (SplFileInfo $fileEntry) {
                return $this->filterTransferFileEntry($fileEntry);
            });

        return $finder;
    }

    protected function filterTransferFileEntry(SplFileInfo $fileEntry): bool
    {
        $filename = $fileEntry->getFilename();
        $filenameWithoutExtension = pathinfo($filename, PATHINFO_FILENAME);

        $transferClassName = $this->buildFullyQualifiedTransferClassName(
            $filenameWithoutExtension,
        );

        return $this->extendsExpectedBaseClass($transferClassName);
    }

    protected function buildFullyQualifiedTransferClassName(string $transferFileName): string
    {
        return sprintf(static::TRANSFER_NAMESPACE_PATTERN, $transferFileName);
    }

    protected function getTransferParentClassName(string $transferClassName): ?string
    {
        if (!class_exists($transferClassName)) {
            return null;
        }

        $reflectionClass = new ReflectionClass($transferClassName);
        $parentClass = $reflectionClass->getParentClass();

        return $parentClass ? $parentClass->getName() : null;
    }

    protected function extendsExpectedBaseClass(string $transferClassName): bool
    {
        $parentClassName = $this->getTransferParentClassName($transferClassName);

        return $parentClassName === $this->getBaseClassToMatch();
    }

    abstract protected function getBaseClassToMatch(): string;
}
