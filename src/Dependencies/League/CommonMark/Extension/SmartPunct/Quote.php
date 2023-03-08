<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * Original code based on the CommonMark JS reference parser (http://bitly.com/commonmark-js)
 *  - (c) John MacFarlane
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enpii\WP_Plugin\Enpii_Base\Dependencies\League\CommonMark\Extension\SmartPunct;

use Enpii\WP_Plugin\Enpii_Base\Dependencies\League\CommonMark\Inline\Element\AbstractStringContainer;

final class Quote extends AbstractStringContainer
{
    public const DOUBLE_QUOTE = '"';
    public const DOUBLE_QUOTE_OPENER = '“';
    public const DOUBLE_QUOTE_CLOSER = '”';

    public const SINGLE_QUOTE = "'";
    public const SINGLE_QUOTE_OPENER = '‘';
    public const SINGLE_QUOTE_CLOSER = '’';
}
