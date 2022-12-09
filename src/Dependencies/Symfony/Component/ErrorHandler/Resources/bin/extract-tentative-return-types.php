#!/usr/bin/env php
<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if ('cli' !== \PHP_SAPI) {
    throw new Exception('This script must be run from the command line.');
}

// Run from the root of the php-src repository, this script generates
// a table with all the methods that have a tentative return type.
//
// Usage: find -name *.stub.php | sort | /path/to/extract-tentative-return-types.php > /path/to/TentativeTypes.php

echo <<<EOPHP
<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enpii\Wp_Plugin\Enpii_Base\Dependencies\Symfony\Component\ErrorHandler\Internal;

/**
 * This class has been generated by extract-tentative-return-types.php.
 *
 * @internal
 */
class TentativeTypes
{
    public const RETURN_TYPES = [

EOPHP;

while (false !== $file = fgets(\STDIN)) {
    $code = file_get_contents(substr($file, 0, -1));

    if (!str_contains($code, '@tentative-return-type')) {
        continue;
    }

    $code = preg_split('{^\s*(?:(?:abstract )?class|interface|trait) ([^\s]++)}m', $code, -1, \PREG_SPLIT_DELIM_CAPTURE);

    if (1 === count($code)) {
        continue;
    }

    for ($i = 1; null !== $class = $code[$i] ?? null; $i += 2) {
        $methods = $code[1 + $i];

        if (!str_contains($methods, '@tentative-return-type')) {
            continue;
        }

        echo "        '$class' => [\n";

        preg_replace_callback('{@tentative-return-type.*?[\s]function ([^(]++)[^)]++\)\s*+:\s*+([^\n;\{]++)}s', function ($m) {
            $m[2] = str_replace(' ', '', $m[2]);
            echo "            '$m[1]' => '$m[2]',\n";

            return '';
        }, $methods);

        echo "        ],\n";
    }
}

echo <<<EOPHP
    ];
}

EOPHP;
