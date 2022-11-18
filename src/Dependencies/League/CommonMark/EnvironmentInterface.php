<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enpii\Wp_Plugin\Enpii_Base\Dependencies\League\CommonMark;

use Enpii\Wp_Plugin\Enpii_Base\Dependencies\League\CommonMark\Block\Parser\BlockParserInterface;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\League\CommonMark\Block\Renderer\BlockRendererInterface;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\League\CommonMark\Delimiter\Processor\DelimiterProcessorCollection;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\League\CommonMark\Event\AbstractEvent;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\League\CommonMark\Inline\Parser\InlineParserInterface;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\League\CommonMark\Inline\Renderer\InlineRendererInterface;

interface EnvironmentInterface
{
    const HTML_INPUT_STRIP = 'strip';
    const HTML_INPUT_ALLOW = 'allow';
    const HTML_INPUT_ESCAPE = 'escape';

    /**
     * @param string|null $key
     * @param mixed       $default
     *
     * @return mixed
     */
    public function getConfig($key = null, $default = null);

    /**
     * @return iterable<BlockParserInterface>
     */
    public function getBlockParsers(): iterable;

    /**
     * @param string $character
     *
     * @return iterable<InlineParserInterface>
     */
    public function getInlineParsersForCharacter(string $character): iterable;

    /**
     * @return DelimiterProcessorCollection
     */
    public function getDelimiterProcessors(): DelimiterProcessorCollection;

    /**
     * @param string $blockClass
     *
     * @return iterable<BlockRendererInterface>
     */
    public function getBlockRenderersForClass(string $blockClass): iterable;

    /**
     * @param string $inlineClass
     *
     * @return iterable<InlineRendererInterface>
     */
    public function getInlineRenderersForClass(string $inlineClass): iterable;

    /**
     * Regex which matches any character which doesn't indicate an inline element
     *
     * This allows us to parse multiple non-special characters at once
     *
     * @return string
     */
    public function getInlineParserCharacterRegex(): string;

    /**
     * Dispatches the given event to listeners
     *
     * @param AbstractEvent $event
     *
     * @return void
     */
    public function dispatch(AbstractEvent $event): void;
}