<?php

declare(strict_types=1);

namespace webignition\YamlDocumentGenerator;

interface DocumentSourceInterface
{
    public function getType(): string;

    /**
     * @return array<mixed>
     */
    public function getData(): array;
}
