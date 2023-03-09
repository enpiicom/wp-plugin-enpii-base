<?php

declare(strict_types=1);

namespace Enpii\WP_Plugin\Enpii_Base\Dependencies\Brick\Math\Exception;

/**
 * Base class for all math exceptions.
 *
 * This class is abstract to ensure that only fine-grained exceptions are thrown throughout the code.
 */
class MathException extends \RuntimeException
{
}
