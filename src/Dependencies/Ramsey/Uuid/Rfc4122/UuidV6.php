<?php

/**
 * This file is part of the ramsey/uuid library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

declare(strict_types=1);

namespace Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Rfc4122;

use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Nonstandard\UuidV6 as NonstandardUuidV6;

/**
 * Reordered time, or version 6, UUIDs include timestamp, clock sequence, and
 * node values that are combined into a 128-bit unsigned integer
 *
 * @link https://datatracker.ietf.org/doc/html/draft-peabody-dispatch-new-uuid-format-04#section-5.1 UUID Version 6
 *
 * @psalm-immutable
 */
final class UuidV6 extends NonstandardUuidV6 implements UuidInterface
{
}
