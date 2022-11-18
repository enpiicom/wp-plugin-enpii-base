<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enpii\Wp_Plugin\Enpii_Base\Dependencies\League\CommonMark\Extension\InlinesOnly;

use Enpii\Wp_Plugin\Enpii_Base\Dependencies\League\CommonMark\Block\Element\AbstractBlock;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\League\CommonMark\Block\Element\Document;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\League\CommonMark\Block\Element\InlineContainerInterface;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\League\CommonMark\Block\Renderer\BlockRendererInterface;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\League\CommonMark\ElementRendererInterface;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\League\CommonMark\Inline\Element\AbstractInline;

/**
 * Simply renders child elements as-is, adding newlines as needed.
 */
final class ChildRenderer implements BlockRendererInterface
{
    public function render(AbstractBlock $block, ElementRendererInterface $htmlRenderer, bool $inTightList = false)
    {
        $out = '';

        if ($block instanceof InlineContainerInterface) {
            /** @var iterable<AbstractInline> $children */
            $children = $block->children();
            $out .= $htmlRenderer->renderInlines($children);
        } else {
            /** @var iterable<AbstractBlock> $children */
            $children = $block->children();
            $out .= $htmlRenderer->renderBlocks($children);
        }

        if (!($block instanceof Document)) {
            $out .= "\n";
        }

        return $out;
    }
}