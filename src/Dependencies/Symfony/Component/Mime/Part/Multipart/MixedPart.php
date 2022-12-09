<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enpii\Wp_Plugin\Enpii_Base\Dependencies\Symfony\Component\Mime\Part\Multipart;

use Enpii\Wp_Plugin\Enpii_Base\Dependencies\Symfony\Component\Mime\Part\AbstractMultipartPart;

/**
 * @author Fabien Potencier <fabien@symfony.com>
 */
final class MixedPart extends AbstractMultipartPart
{
    public function getMediaSubtype(): string
    {
        return 'mixed';
    }
}
