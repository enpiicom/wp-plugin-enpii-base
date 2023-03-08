<?php declare(strict_types=1);

/*
 * This file is part of the Enpii\WP_Plugin\Enpii_Base\Dependencies\Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enpii\WP_Plugin\Enpii_Base\Dependencies\Monolog\Handler\FingersCrossed;

use Enpii\WP_Plugin\Enpii_Base\Dependencies\Monolog\Logger;
use Enpii\WP_Plugin\Enpii_Base\Dependencies\Psr\Log\LogLevel;

/**
 * Error level based activation strategy.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * @phpstan-import-type Level from \Enpii\WP_Plugin\Enpii_Base\Dependencies\Monolog\Logger
 * @phpstan-import-type LevelName from \Enpii\WP_Plugin\Enpii_Base\Dependencies\Monolog\Logger
 */
class ErrorLevelActivationStrategy implements ActivationStrategyInterface
{
    /**
     * @var Level
     */
    private $actionLevel;

    /**
     * @param int|string $actionLevel Level or name or value
     *
     * @phpstan-param Level|LevelName|LogLevel::* $actionLevel
     */
    public function __construct($actionLevel)
    {
        $this->actionLevel = Logger::toMonologLevel($actionLevel);
    }

    public function isHandlerActivated(array $record): bool
    {
        return $record['level'] >= $this->actionLevel;
    }
}
