<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\XmlValidator;

use DOMDocument;
use LibXMLError;
use RuntimeException;
use Throwable;

class XmlXsdSchemaValidator implements XmlValidatorInterface
{
    /**
     * @var string
     */
    protected const XML_SCHEMA_INSTANCE_NAMESPACE_ATTRIBUTE = 'xmlns:xsi="https://www.w3.org/2001/XMLSchema-instance"';

    /**
     * @var string
     */
    protected const XML_SCHEMA_INSTANCE_NAMESPACE_ATTRIBUTE_SHIM = 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"';

    /**
     * @var array<string>
     */
    protected $errors = [];

    public function validate(string $file, string $schema): void
    {
        $this->resetErrors();
        $xmlDocument = $this->createDomDocument($file);

        try {
            if (!$xmlDocument->schemaValidate($schema)) {
                $this->logXmlErrors();
            }
        } catch (Throwable $e) {
            $this->logError($e->getMessage(), $file);
        }
    }

    public function isValid(): bool
    {
        return !$this->errors;
    }

    /**
     * @return array<string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    protected function createDomDocument(string $filePath): DOMDocument
    {
        $xmlDocument = new DOMDocument();
        $xmlDocument->loadXML(
            $this->readXmlFileContent($filePath),
        );

        return $xmlDocument;
    }

    /**
     * @param string $filePath
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    protected function readXmlFileContent(string $filePath): string
    {
        $fileContent = file_get_contents($filePath);

        if ($fileContent === false) {
            throw new RuntimeException(
                sprintf('File "%s" could not be read.', $filePath),
            );
        }

        return $this->shimXmlSchemaInstanceNamespace($fileContent);
    }

    /**
     * Shims the incorrectly spelled XML schema instance namespace.
     *
     * @param string $fileContent
     *
     * @return string
     */
    protected function shimXmlSchemaInstanceNamespace(string $fileContent): string
    {
        return str_replace(
            static::XML_SCHEMA_INSTANCE_NAMESPACE_ATTRIBUTE,
            static::XML_SCHEMA_INSTANCE_NAMESPACE_ATTRIBUTE_SHIM,
            $fileContent,
        );
    }

    protected function logXmlErrors(): void
    {
        /** @var array<\LibXMLError> $errors */
        $errors = libxml_get_errors();

        foreach ($errors as $error) {
            $this->logXmlError($error);
        }

        libxml_clear_errors();
    }

    protected function resetErrors(): void
    {
        $this->errors = [];
    }

    protected function logXmlError(LibXMLError $error): void
    {
        $message = sprintf('%s:%s', $error->code, $error->message);

        $this->logError($message, $error->file);
    }

    protected function logError(string $errorMessage, string $fileName): void
    {
        $this->errors[] = sprintf('"%s" in %s', $errorMessage, $fileName);
    }
}
