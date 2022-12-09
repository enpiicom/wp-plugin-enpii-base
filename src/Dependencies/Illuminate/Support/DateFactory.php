<?php

namespace Enpii\Wp_Plugin\Enpii_Base\Dependencies\Illuminate\Support;

use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon\Factory;
use InvalidArgumentException;

/**
 * @see https://carbon.nesbot.com/docs/
 * @see https://github.com/briannesbitt/Carbon/blob/master/src/Carbon/Factory.php
 *
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon create($year = 0, $month = 1, $day = 1, $hour = 0, $minute = 0, $second = 0, $tz = null)
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon createFromDate($year = null, $month = null, $day = null, $tz = null)
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon|false createFromFormat($format, $time, $tz = null)
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon createFromTime($hour = 0, $minute = 0, $second = 0, $tz = null)
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon createFromTimeString($time, $tz = null)
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon createFromTimestamp($timestamp, $tz = null)
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon createFromTimestampMs($timestamp, $tz = null)
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon createFromTimestampUTC($timestamp)
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon createMidnightDate($year = null, $month = null, $day = null, $tz = null)
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon|false createSafe($year = null, $month = null, $day = null, $hour = null, $minute = null, $second = null, $tz = null)
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon disableHumanDiffOption($humanDiffOption)
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon enableHumanDiffOption($humanDiffOption)
 * @method static mixed executeWithLocale($locale, $func)
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon fromSerialized($value)
 * @method static array getAvailableLocales()
 * @method static array getDays()
 * @method static int getHumanDiffOptions()
 * @method static array getIsoUnits()
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon getLastErrors()
 * @method static string getLocale()
 * @method static int getMidDayAt()
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon getTestNow()
 * @method static \Enpii\Wp_Plugin\Enpii_Base\Dependencies\Symfony\Component\Translation\TranslatorInterface getTranslator()
 * @method static int getWeekEndsAt()
 * @method static int getWeekStartsAt()
 * @method static array getWeekendDays()
 * @method static bool hasFormat($date, $format)
 * @method static bool hasMacro($name)
 * @method static bool hasRelativeKeywords($time)
 * @method static bool hasTestNow()
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon instance($date)
 * @method static bool isImmutable()
 * @method static bool isModifiableUnit($unit)
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon isMutable()
 * @method static bool isStrictModeEnabled()
 * @method static bool localeHasDiffOneDayWords($locale)
 * @method static bool localeHasDiffSyntax($locale)
 * @method static bool localeHasDiffTwoDayWords($locale)
 * @method static bool localeHasPeriodSyntax($locale)
 * @method static bool localeHasShortUnits($locale)
 * @method static void macro($name, $macro)
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon|null make($var)
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon maxValue()
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon minValue()
 * @method static void mixin($mixin)
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon now($tz = null)
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon parse($time = null, $tz = null)
 * @method static string pluralUnit(string $unit)
 * @method static void resetMonthsOverflow()
 * @method static void resetToStringFormat()
 * @method static void resetYearsOverflow()
 * @method static void serializeUsing($callback)
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon setHumanDiffOptions($humanDiffOptions)
 * @method static bool setLocale($locale)
 * @method static void setMidDayAt($hour)
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon setTestNow($testNow = null)
 * @method static void setToStringFormat($format)
 * @method static void setTranslator(\Enpii\Wp_Plugin\Enpii_Base\Dependencies\Symfony\Component\Translation\TranslatorInterface $translator)
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon setUtf8($utf8)
 * @method static void setWeekEndsAt($day)
 * @method static void setWeekStartsAt($day)
 * @method static void setWeekendDays($days)
 * @method static bool shouldOverflowMonths()
 * @method static bool shouldOverflowYears()
 * @method static string singularUnit(string $unit)
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon today($tz = null)
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon tomorrow($tz = null)
 * @method static void useMonthsOverflow($monthsOverflow = true)
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon useStrictMode($strictModeEnabled = true)
 * @method static void useYearsOverflow($yearsOverflow = true)
 * @method static Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon yesterday($tz = null)
 */
class DateFactory
{
    /**
     * The default class that will be used for all created dates.
     *
     * @var string
     */
    const DEFAULT_CLASS_NAME = Carbon::class;

    /**
     * The type (class) of dates that should be created.
     *
     * @var string
     */
    protected static $dateClass;

    /**
     * This callable may be used to intercept date creation.
     *
     * @var callable
     */
    protected static $callable;

    /**
     * The Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon factory that should be used when creating dates.
     *
     * @var object
     */
    protected static $factory;

    /**
     * Use the given handler when generating dates (class name, callable, or factory).
     *
     * @param  mixed  $handler
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public static function use($handler)
    {
        if (is_callable($handler) && is_object($handler)) {
            return static::useCallable($handler);
        } elseif (is_string($handler)) {
            return static::useClass($handler);
        } elseif ($handler instanceof Factory) {
            return static::useFactory($handler);
        }

        throw new InvalidArgumentException('Invalid date creation handler. Please provide a class name, callable, or Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon factory.');
    }

    /**
     * Use the default date class when generating dates.
     *
     * @return void
     */
    public static function useDefault()
    {
        static::$dateClass = null;
        static::$callable = null;
        static::$factory = null;
    }

    /**
     * Execute the given callable on each date creation.
     *
     * @param  callable  $callable
     * @return void
     */
    public static function useCallable(callable $callable)
    {
        static::$callable = $callable;

        static::$dateClass = null;
        static::$factory = null;
    }

    /**
     * Use the given date type (class) when generating dates.
     *
     * @param  string  $dateClass
     * @return void
     */
    public static function useClass($dateClass)
    {
        static::$dateClass = $dateClass;

        static::$factory = null;
        static::$callable = null;
    }

    /**
     * Use the given Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon factory when generating dates.
     *
     * @param  object  $factory
     * @return void
     */
    public static function useFactory($factory)
    {
        static::$factory = $factory;

        static::$dateClass = null;
        static::$callable = null;
    }

    /**
     * Handle dynamic calls to generate dates.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public function __call($method, $parameters)
    {
        $defaultClassName = static::DEFAULT_CLASS_NAME;

        // Using callable to generate dates...
        if (static::$callable) {
            return call_user_func(static::$callable, $defaultClassName::$method(...$parameters));
        }

        // Using Enpii\Wp_Plugin\Enpii_Base\Dependencies\Carbon factory to generate dates...
        if (static::$factory) {
            return static::$factory->$method(...$parameters);
        }

        $dateClass = static::$dateClass ?: $defaultClassName;

        // Check if date can be created using public class method...
        if (method_exists($dateClass, $method) ||
            method_exists($dateClass, 'hasMacro') && $dateClass::hasMacro($method)) {
            return $dateClass::$method(...$parameters);
        }

        // If that fails, create the date with the default class..
        $date = $defaultClassName::$method(...$parameters);

        // If the configured class has an "instance" method, we'll try to pass our date into there...
        if (method_exists($dateClass, 'instance')) {
            return $dateClass::instance($date);
        }

        // Otherwise, assume the configured class has a DateTime compatible constructor...
        return new $dateClass($date->format('Y-m-d H:i:s.u'), $date->getTimezone());
    }
}
