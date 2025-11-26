<?php

declare(strict_types=1);

namespace webignition\YamlDocumentGenerator\Tests\Unit;

use PHPUnit\Framework\TestCase;
use webignition\YamlDocumentGenerator\YamlGenerator;

class YamlGeneratorTest extends TestCase
{
    /**
     * @dataProvider generateDataProvider
     *
     * @param array<mixed> $data
     */
    public function testGenerate(
        array $data,
        int $inlineDepth,
        int $indentSize,
        string $expectedString
    ): void {
        $generator = new YamlGenerator();
        $generator->setInlineDepth($inlineDepth);
        $generator->setIndentSize($indentSize);

        $this->assertSame($expectedString, $generator->generate($data));
    }

    /**
     * @return array<mixed>
     */
    public static function generateDataProvider(): array
    {
        return [
            'empty document' => [
                'data' => [],
                'inlineDepth' => YamlGenerator::DEFAULT_INLINE_DEPTH,
                'indentSize' => YamlGenerator::DEFAULT_INDENT_SIZE,
                'expectedString' => '---' . "\n"
                    . '{  }' . "\n"
                    . '...' . "\n",
            ],
            'single-level document' => [
                'data' => [
                    'level1key1' => 'level1value1',
                ],
                'inlineDepth' => YamlGenerator::DEFAULT_INLINE_DEPTH,
                'indentSize' => YamlGenerator::DEFAULT_INDENT_SIZE,
                'expectedString' => '---' . "\n"
                    . 'level1key1: level1value1' . "\n"
                    . '...' . "\n",
            ],
            'two-level document' => [
                'data' => [
                    'level1key1' => 'level1value1',
                    'level1key2' => [
                        'level2key1' => 'level2value1',
                    ],
                ],
                'inlineDepth' => YamlGenerator::DEFAULT_INLINE_DEPTH,
                'indentSize' => YamlGenerator::DEFAULT_INDENT_SIZE,
                'expectedString' => '---' . "\n"
                    . 'level1key1: level1value1' . "\n"
                    . 'level1key2:' . "\n"
                    . '  level2key1: level2value1' . "\n"
                    . '...' . "\n",
            ],
            'three-level document, default inline depth, default indent size' => [
                'data' => [
                    'level1key1' => 'level1value1',
                    'level1key2' => [
                        'level2key1' => 'level2value1',
                        'level2key2' => [
                            'level3key1' => 'level3value1',
                        ],
                    ],
                ],
                'inlineDepth' => YamlGenerator::DEFAULT_INLINE_DEPTH,
                'indentSize' => YamlGenerator::DEFAULT_INDENT_SIZE,
                'expectedString' => '---' . "\n"
                    . 'level1key1: level1value1' . "\n"
                    . 'level1key2:' . "\n"
                    . '  level2key1: level2value1' . "\n"
                    . '  level2key2:' . "\n"
                    . '    level3key1: level3value1' . "\n"
                    . '...' . "\n",
            ],
            'three-level document, default inline depth, non-default indent size' => [
                'data' => [
                    'level1key1' => 'level1value1',
                    'level1key2' => [
                        'level2key1' => 'level2value1',
                        'level2key2' => [
                            'level3key1' => 'level3value1',
                        ],
                    ],
                ],
                'inlineDepth' => YamlGenerator::DEFAULT_INLINE_DEPTH,
                'indentSize' => 4,
                'expectedString' => '---' . "\n"
                    . 'level1key1: level1value1' . "\n"
                    . 'level1key2:' . "\n"
                    . '    level2key1: level2value1' . "\n"
                    . '    level2key2:' . "\n"
                    . '        level3key1: level3value1' . "\n"
                    . '...' . "\n",
            ],
            'three-level document, non-default inline depth, non-default indent size' => [
                'data' => [
                    'level1key1' => 'level1value1',
                    'level1key2' => [
                        'level2key1' => 'level2value1',
                        'level2key2' => [
                            'level3key1' => 'level3value1',
                        ],
                    ],
                ],
                'inlineDepth' => 2,
                'indentSize' => 4,
                'expectedString' => '---' . "\n"
                    . 'level1key1: level1value1' . "\n"
                    . 'level1key2:' . "\n"
                    . '    level2key1: level2value1' . "\n"
                    . '    level2key2: { level3key1: level3value1 }' . "\n"
                    . '...' . "\n",
            ],
        ];
    }
}
