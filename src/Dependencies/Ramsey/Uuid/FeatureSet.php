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

namespace Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid;

use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Builder\FallbackBuilder;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Builder\UuidBuilderInterface;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Codec\CodecInterface;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Codec\GuidStringCodec;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Codec\StringCodec;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Converter\Number\GenericNumberConverter;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Converter\NumberConverterInterface;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Converter\Time\GenericTimeConverter;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Converter\Time\PhpTimeConverter;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Converter\Time\UnixTimeConverter;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Converter\TimeConverterInterface;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Generator\DceSecurityGenerator;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Generator\DceSecurityGeneratorInterface;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Generator\NameGeneratorFactory;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Generator\NameGeneratorInterface;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Generator\PeclUuidNameGenerator;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Generator\PeclUuidRandomGenerator;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Generator\PeclUuidTimeGenerator;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Generator\RandomGeneratorFactory;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Generator\RandomGeneratorInterface;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Generator\TimeGeneratorFactory;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Generator\TimeGeneratorInterface;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Generator\UnixTimeGenerator;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Guid\GuidBuilder;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Math\BrickMathCalculator;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Math\CalculatorInterface;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Nonstandard\UuidBuilder as NonstandardUuidBuilder;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Provider\Dce\SystemDceSecurityProvider;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Provider\DceSecurityProviderInterface;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Provider\Node\FallbackNodeProvider;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Provider\Node\RandomNodeProvider;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Provider\Node\SystemNodeProvider;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Provider\NodeProviderInterface;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Provider\Time\SystemTimeProvider;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Provider\TimeProviderInterface;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Rfc4122\UuidBuilder as Rfc4122UuidBuilder;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Validator\GenericValidator;
use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Ramsey\Uuid\Validator\ValidatorInterface;

use const PHP_INT_SIZE;

/**
 * FeatureSet detects and exposes available features in the current environment
 *
 * A feature set is used by UuidFactory to determine the available features and
 * capabilities of the environment.
 */
class FeatureSet
{
    private ?TimeProviderInterface $timeProvider = null;
    private CalculatorInterface $calculator;
    private CodecInterface $codec;
    private DceSecurityGeneratorInterface $dceSecurityGenerator;
    private NameGeneratorInterface $nameGenerator;
    private NodeProviderInterface $nodeProvider;
    private NumberConverterInterface $numberConverter;
    private RandomGeneratorInterface $randomGenerator;
    private TimeConverterInterface $timeConverter;
    private TimeGeneratorInterface $timeGenerator;
    private TimeGeneratorInterface $unixTimeGenerator;
    private UuidBuilderInterface $builder;
    private ValidatorInterface $validator;

    /**
     * @param bool $useGuids True build UUIDs using the GuidStringCodec
     * @param bool $force32Bit True to force the use of 32-bit functionality
     *     (primarily for testing purposes)
     * @param bool $forceNoBigNumber (obsolete)
     * @param bool $ignoreSystemNode True to disable attempts to check for the
     *     system node ID (primarily for testing purposes)
     * @param bool $enablePecl True to enable the use of the PeclUuidTimeGenerator
     *     to generate version 1 UUIDs
     */
    public function __construct(
        bool $useGuids = false,
        private bool $force32Bit = false,
        bool $forceNoBigNumber = false,
        private bool $ignoreSystemNode = false,
        private bool $enablePecl = false
    ) {
        $this->randomGenerator = $this->buildRandomGenerator();
        $this->setCalculator(new BrickMathCalculator());
        $this->builder = $this->buildUuidBuilder($useGuids);
        $this->codec = $this->buildCodec($useGuids);
        $this->nodeProvider = $this->buildNodeProvider();
        $this->nameGenerator = $this->buildNameGenerator();
        $this->setTimeProvider(new SystemTimeProvider());
        $this->setDceSecurityProvider(new SystemDceSecurityProvider());
        $this->validator = new GenericValidator();

        assert($this->timeProvider !== null);
        $this->unixTimeGenerator = $this->buildUnixTimeGenerator($this->timeProvider);
    }

    /**
     * Returns the builder configured for this environment
     */
    public function getBuilder(): UuidBuilderInterface
    {
        return $this->builder;
    }

    /**
     * Returns the calculator configured for this environment
     */
    public function getCalculator(): CalculatorInterface
    {
        return $this->calculator;
    }

    /**
     * Returns the codec configured for this environment
     */
    public function getCodec(): CodecInterface
    {
        return $this->codec;
    }

    /**
     * Returns the DCE Security generator configured for this environment
     */
    public function getDceSecurityGenerator(): DceSecurityGeneratorInterface
    {
        return $this->dceSecurityGenerator;
    }

    /**
     * Returns the name generator configured for this environment
     */
    public function getNameGenerator(): NameGeneratorInterface
    {
        return $this->nameGenerator;
    }

    /**
     * Returns the node provider configured for this environment
     */
    public function getNodeProvider(): NodeProviderInterface
    {
        return $this->nodeProvider;
    }

    /**
     * Returns the number converter configured for this environment
     */
    public function getNumberConverter(): NumberConverterInterface
    {
        return $this->numberConverter;
    }

    /**
     * Returns the random generator configured for this environment
     */
    public function getRandomGenerator(): RandomGeneratorInterface
    {
        return $this->randomGenerator;
    }

    /**
     * Returns the time converter configured for this environment
     */
    public function getTimeConverter(): TimeConverterInterface
    {
        return $this->timeConverter;
    }

    /**
     * Returns the time generator configured for this environment
     */
    public function getTimeGenerator(): TimeGeneratorInterface
    {
        return $this->timeGenerator;
    }

    /**
     * Returns the Unix Epoch time generator configured for this environment
     */
    public function getUnixTimeGenerator(): TimeGeneratorInterface
    {
        return $this->unixTimeGenerator;
    }

    /**
     * Returns the validator configured for this environment
     */
    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    /**
     * Sets the calculator to use in this environment
     */
    public function setCalculator(CalculatorInterface $calculator): void
    {
        $this->calculator = $calculator;
        $this->numberConverter = $this->buildNumberConverter($calculator);
        $this->timeConverter = $this->buildTimeConverter($calculator);

        /** @psalm-suppress RedundantPropertyInitializationCheck */
        if (isset($this->timeProvider)) {
            $this->timeGenerator = $this->buildTimeGenerator($this->timeProvider);
        }
    }

    /**
     * Sets the DCE Security provider to use in this environment
     */
    public function setDceSecurityProvider(DceSecurityProviderInterface $dceSecurityProvider): void
    {
        $this->dceSecurityGenerator = $this->buildDceSecurityGenerator($dceSecurityProvider);
    }

    /**
     * Sets the node provider to use in this environment
     */
    public function setNodeProvider(NodeProviderInterface $nodeProvider): void
    {
        $this->nodeProvider = $nodeProvider;

        if (isset($this->timeProvider)) {
            $this->timeGenerator = $this->buildTimeGenerator($this->timeProvider);
        }
    }

    /**
     * Sets the time provider to use in this environment
     */
    public function setTimeProvider(TimeProviderInterface $timeProvider): void
    {
        $this->timeProvider = $timeProvider;
        $this->timeGenerator = $this->buildTimeGenerator($timeProvider);
    }

    /**
     * Set the validator to use in this environment
     */
    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }

    /**
     * Returns a codec configured for this environment
     *
     * @param bool $useGuids Whether to build UUIDs using the GuidStringCodec
     */
    private function buildCodec(bool $useGuids = false): CodecInterface
    {
        if ($useGuids) {
            return new GuidStringCodec($this->builder);
        }

        return new StringCodec($this->builder);
    }

    /**
     * Returns a DCE Security generator configured for this environment
     */
    private function buildDceSecurityGenerator(
        DceSecurityProviderInterface $dceSecurityProvider
    ): DceSecurityGeneratorInterface {
        return new DceSecurityGenerator(
            $this->numberConverter,
            $this->timeGenerator,
            $dceSecurityProvider
        );
    }

    /**
     * Returns a node provider configured for this environment
     */
    private function buildNodeProvider(): NodeProviderInterface
    {
        if ($this->ignoreSystemNode) {
            return new RandomNodeProvider();
        }

        return new FallbackNodeProvider([
            new SystemNodeProvider(),
            new RandomNodeProvider(),
        ]);
    }

    /**
     * Returns a number converter configured for this environment
     */
    private function buildNumberConverter(CalculatorInterface $calculator): NumberConverterInterface
    {
        return new GenericNumberConverter($calculator);
    }

    /**
     * Returns a random generator configured for this environment
     */
    private function buildRandomGenerator(): RandomGeneratorInterface
    {
        if ($this->enablePecl) {
            return new PeclUuidRandomGenerator();
        }

        return (new RandomGeneratorFactory())->getGenerator();
    }

    /**
     * Returns a time generator configured for this environment
     *
     * @param TimeProviderInterface $timeProvider The time provider to use with
     *     the time generator
     */
    private function buildTimeGenerator(TimeProviderInterface $timeProvider): TimeGeneratorInterface
    {
        if ($this->enablePecl) {
            return new PeclUuidTimeGenerator();
        }

        return (new TimeGeneratorFactory(
            $this->nodeProvider,
            $this->timeConverter,
            $timeProvider
        ))->getGenerator();
    }

    /**
     * Returns a Unix Epoch time generator configured for this environment
     *
     * @param TimeProviderInterface $timeProvider The time provider to use with
     *     the time generator
     */
    private function buildUnixTimeGenerator(TimeProviderInterface $timeProvider): TimeGeneratorInterface
    {
        return new UnixTimeGenerator(
            new UnixTimeConverter(new BrickMathCalculator()),
            $timeProvider,
            $this->randomGenerator,
        );
    }

    /**
     * Returns a name generator configured for this environment
     */
    private function buildNameGenerator(): NameGeneratorInterface
    {
        if ($this->enablePecl) {
            return new PeclUuidNameGenerator();
        }

        return (new NameGeneratorFactory())->getGenerator();
    }

    /**
     * Returns a time converter configured for this environment
     */
    private function buildTimeConverter(CalculatorInterface $calculator): TimeConverterInterface
    {
        $genericConverter = new GenericTimeConverter($calculator);

        if ($this->is64BitSystem()) {
            return new PhpTimeConverter($calculator, $genericConverter);
        }

        return $genericConverter;
    }

    /**
     * Returns a UUID builder configured for this environment
     *
     * @param bool $useGuids Whether to build UUIDs using the GuidStringCodec
     */
    private function buildUuidBuilder(bool $useGuids = false): UuidBuilderInterface
    {
        if ($useGuids) {
            return new GuidBuilder($this->numberConverter, $this->timeConverter);
        }

        return new FallbackBuilder([
            new Rfc4122UuidBuilder($this->numberConverter, $this->timeConverter),
            new NonstandardUuidBuilder($this->numberConverter, $this->timeConverter),
        ]);
    }

    /**
     * Returns true if the PHP build is 64-bit
     */
    private function is64BitSystem(): bool
    {
        return PHP_INT_SIZE === 8 && !$this->force32Bit;
    }
}
