<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer\Business\Model\Generator;

use Codeception\Test\Unit;
use RuntimeException;
use Spryker\Zed\Transfer\Business\Model\Generator\ClassGenerator;
use Spryker\Zed\Transfer\Business\Model\Generator\DataBuilderClassGenerator;
use Spryker\Zed\Transfer\Business\Model\Generator\DataBuilderDefinition;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Transfer
 * @group Business
 * @group Model
 * @group Generator
 * @group ClassGeneratorTest
 * Add your own group annotations below this line
 */
class ClassGeneratorTest extends Unit
{
    public function setUp(): void
    {
        $this->removeTargetDirectory();
    }

    public function tearDown(): void
    {
        $this->removeTargetDirectory();
    }

    public function testGenerateShouldCreateTargetDirectoryIfNotExist(): void
    {
        $transferGenerator = new DataBuilderClassGenerator($this->getFixtureDirectory());
        $transferDefinition = new DataBuilderDefinition();
        $transferDefinition->setDefinition([
            'name' => 'Name',
        ]);
        $transferGenerator->generate($transferDefinition);

        $this->assertTrue(is_dir($this->getFixtureDirectory()));
    }

    public function testClassGeneratorInitializesWithNewTemplatesPath(): void
    {
        $generator = new ClassGenerator($this->getFixtureDirectory());

        $this->assertInstanceOf(ClassGenerator::class, $generator);
    }

    public function testClassGeneratorFallsBackToLegacyTemplatesPath(): void
    {
        $generator = new class ($this->getFixtureDirectory()) extends ClassGenerator {
            public const TWIG_TEMPLATES_LOCATION = '/non-existent-primary/';

            // Fallback points to the same real templates location
            public const TWIG_TEMPLATES_LOCATION_FALLBACK = '/../../../../../../../templates/generator/';
        };

        $this->assertInstanceOf(ClassGenerator::class, $generator);
    }

    public function testClassGeneratorThrowsExceptionWhenBothTemplatePathsNotFound(): void
    {
        $this->expectException(RuntimeException::class);

        new class ($this->getFixtureDirectory()) extends ClassGenerator {
            public const TWIG_TEMPLATES_LOCATION = '/non-existent-primary/';

            public const TWIG_TEMPLATES_LOCATION_FALLBACK = '/non-existent-fallback/';
        };
    }

    public function testDataBuilderClassGeneratorInitializesWithNewTemplatesPath(): void
    {
        $generator = new DataBuilderClassGenerator($this->getFixtureDirectory());

        $this->assertInstanceOf(DataBuilderClassGenerator::class, $generator);
    }

    public function testDataBuilderClassGeneratorFallsBackToLegacyTemplatesPath(): void
    {
        $generator = new class ($this->getFixtureDirectory()) extends DataBuilderClassGenerator {
            public const TWIG_TEMPLATES_LOCATION = '/non-existent-primary/';

            public const TWIG_TEMPLATES_LOCATION_FALLBACK = '/../../../../../../../templates/generator/';
        };

        $this->assertInstanceOf(DataBuilderClassGenerator::class, $generator);
    }

    public function testDataBuilderClassGeneratorThrowsExceptionWhenBothTemplatePathsNotFound(): void
    {
        $this->expectException(RuntimeException::class);

        new class ($this->getFixtureDirectory()) extends DataBuilderClassGenerator {
            public const TWIG_TEMPLATES_LOCATION = '/non-existent-primary/';

            public const TWIG_TEMPLATES_LOCATION_FALLBACK = '/non-existent-fallback/';
        };
    }

    private function removeTargetDirectory(): void
    {
        if (is_dir($this->getFixtureDirectory())) {
            $filesystem = new Filesystem();
            $filesystem->remove($this->getFixtureDirectory());
        }
    }

    private function getFixtureDirectory(): string
    {
        return __DIR__ . '/FixturesTest/';
    }
}
