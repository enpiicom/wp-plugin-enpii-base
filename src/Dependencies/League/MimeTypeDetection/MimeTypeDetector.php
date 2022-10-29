<?php

declare(strict_types=1);

namespace Enpii\Wp_Plugin\Enpii_Base\Dependencies\League\MimeTypeDetection;

interface MimeTypeDetector
{
    /**
     * @param string|resource $contents
     */
    public function detectMimeType(string $path, $contents): ?string;

    public function detectMimeTypeFromBuffer(string $contents): ?string;

    public function detectMimeTypeFromPath(string $path): ?string;

    public function detectMimeTypeFromFile(string $path): ?string;
}
