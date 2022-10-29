<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enpii\Wp_Plugin\Enpii_Base\Dependencies\League\CommonMark\Extension\Mention\Generator;

use Enpii\Wp_Plugin\Enpii_Base\Dependencies\League\CommonMark\Extension\Mention\Mention;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\League\CommonMark\Inline\Element\AbstractInline;

final class StringTemplateLinkGenerator implements MentionGeneratorInterface
{
    /** @var string */
    private $urlTemplate;

    public function __construct(string $urlTemplate)
    {
        $this->urlTemplate = $urlTemplate;
    }

    public function generateMention(Mention $mention): ?AbstractInline
    {
        return $mention->setUrl(\sprintf($this->urlTemplate, $mention->getIdentifier()));
    }
}
