<?php

declare(strict_types=1);

namespace webignition\YamlDocumentGenerator\Tests\Unit;

use PHPUnit\Framework\TestCase;
use webignition\YamlDocumentGenerator\DocumentSourceInterface;
use webignition\YamlDocumentGenerator\YamlGenerator;

class YamlGeneratorTest extends TestCase
{
    /**
     * @dataProvider generateDataProvider
     */
    public function testGenerate(
        DocumentSourceInterface $documentSource,
        int $inlineDepth,
        int $indentSize,
        string $expectedString
    ) {
        $generator = new YamlGenerator();
        $generator->setInlineDepth($inlineDepth);
        $generator->setIndentSize($indentSize);

        $this->assertSame($expectedString, $generator->generate($documentSource));
    }

    public function generateDataProvider(): array
    {
        return [
            'empty document' => [
                'documentSource' => $this->createDocumentSource('empty-document', []),
                'inlineDepth' => YamlGenerator::DEFAULT_INLINE_DEPTH,
                'indentSize' => YamlGenerator::DEFAULT_INDENT_SIZE,
                'expectedString' =>
                    '---' . "\n" .
                    'type: empty-document' . "\n" .
                    '...' . "\n",
            ],
            'single-level document' => [
                'documentSource' => $this->createDocumentSource(
                    'single-level-document',
                    [
                        'level1key1' => 'level1value1',
                    ]
                ),
                'inlineDepth' => YamlGenerator::DEFAULT_INLINE_DEPTH,
                'indentSize' => YamlGenerator::DEFAULT_INDENT_SIZE,
                'expectedString' =>
                    '---' . "\n" .
                    'type: single-level-document' . "\n" .
                    'level1key1: level1value1' . "\n" .
                    '...' . "\n",
            ],
            'two-level document' => [
                'documentSource' => $this->createDocumentSource(
                    'two-level-document',
                    [
                        'level1key1' => 'level1value1',
                        'level1key2' => [
                            'level2key1' => 'level2value1',
                        ],

                    ]
                ),
                'inlineDepth' => YamlGenerator::DEFAULT_INLINE_DEPTH,
                'indentSize' => YamlGenerator::DEFAULT_INDENT_SIZE,
                'expectedString' =>
                    '---' . "\n" .
                    'type: two-level-document' . "\n" .
                    'level1key1: level1value1' . "\n" .
                    'level1key2:' . "\n" .
                    '  level2key1: level2value1' . "\n" .
                    '...' . "\n",
            ],
            'three-level document, default inline depth, default indent size' => [
                'documentSource' => $this->createDocumentSource(
                    'three-level-document',
                    [
                        'level1key1' => 'level1value1',
                        'level1key2' => [
                            'level2key1' => 'level2value1',
                            'level2key2' => [
                                'level3key1' => 'level3value1',
                            ],
                        ],

                    ]
                ),
                'inlineDepth' => YamlGenerator::DEFAULT_INLINE_DEPTH,
                'indentSize' => YamlGenerator::DEFAULT_INDENT_SIZE,
                'expectedString' =>
                    '---' . "\n" .
                    'type: three-level-document' . "\n" .
                    'level1key1: level1value1' . "\n" .
                    'level1key2:' . "\n" .
                    '  level2key1: level2value1' . "\n" .
                    '  level2key2:' . "\n" .
                    '    level3key1: level3value1' . "\n" .
                    '...' . "\n",
            ],
            'three-level document, default inline depth, non-default indent size' => [
                'documentSource' => $this->createDocumentSource(
                    'three-level-document',
                    [
                        'level1key1' => 'level1value1',
                        'level1key2' => [
                            'level2key1' => 'level2value1',
                            'level2key2' => [
                                'level3key1' => 'level3value1',
                            ],
                        ],

                    ]
                ),
                'inlineDepth' => YamlGenerator::DEFAULT_INLINE_DEPTH,
                'indentSize' => 4,
                'expectedString' =>
                    '---' . "\n" .
                    'type: three-level-document' . "\n" .
                    'level1key1: level1value1' . "\n" .
                    'level1key2:' . "\n" .
                    '    level2key1: level2value1' . "\n" .
                    '    level2key2:' . "\n" .
                    '        level3key1: level3value1' . "\n" .
                    '...' . "\n",
            ],
            'three-level document, non-default inline depth, non-default indent size' => [
                'documentSource' => $this->createDocumentSource(
                    'three-level-document',
                    [
                        'level1key1' => 'level1value1',
                        'level1key2' => [
                            'level2key1' => 'level2value1',
                            'level2key2' => [
                                'level3key1' => 'level3value1',
                            ],
                        ],

                    ]
                ),
                'inlineDepth' => 2,
                'indentSize' => 4,
                'expectedString' =>
                    '---' . "\n" .
                    'type: three-level-document' . "\n" .
                    'level1key1: level1value1' . "\n" .
                    'level1key2:' . "\n" .
                    '    level2key1: level2value1' . "\n" .
                    '    level2key2: { level3key1: level3value1 }' . "\n" .
                    '...' . "\n",
            ],
        ];
    }

    /**
     * @param string $type
     * @param array<mixed> $data
     *
     * @return DocumentSourceInterface
     */
    private function createDocumentSource(string $type, array $data): DocumentSourceInterface
    {
        $documentSource = \Mockery::mock(DocumentSourceInterface::class);

        $documentSource
            ->shouldReceive('getType')
            ->andReturn($type);

        $documentSource
            ->shouldReceive('getData')
            ->andReturn($data);

        return $documentSource;
    }
}
