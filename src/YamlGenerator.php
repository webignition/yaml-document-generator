<?php

declare(strict_types=1);

namespace webignition\YamlDocumentGenerator;

use Symfony\Component\Yaml\Yaml;
use webignition\BasilRunnerDocuments\DocumentInterface;

class YamlGenerator
{
    public const DEFAULT_INLINE_DEPTH = 9;
    public const DEFAULT_INDENT_SIZE = 2;
    private const DOCUMENT_START = '---';
    private const DOCUMENT_END = '...';

    private int $inlineDepth = self::DEFAULT_INLINE_DEPTH;
    private int $indentSize = self::DEFAULT_INDENT_SIZE;

    public function generate(DocumentInterface $documentSource): string
    {
        return
            self::DOCUMENT_START . "\n" .
            trim(Yaml::dump(
                array_merge(
                    [
                        'type' => $documentSource->getType(),
                    ],
                    $documentSource->getData()
                ),
                $this->inlineDepth,
                $this->indentSize
            )) . "\n" .
            self::DOCUMENT_END . "\n";
    }

    public function setInlineDepth(int $inlineDepth): void
    {
        $this->inlineDepth = $inlineDepth;
    }

    public function setIndentSize(int $indentSize): void
    {
        $this->indentSize = $indentSize;
    }
}
