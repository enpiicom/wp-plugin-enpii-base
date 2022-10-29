<?php

if (\PHP_VERSION_ID < 80000 && \extension_loaded('tokenizer')) {
    class PhpToken extends Enpii\Wp_Plugin\Enpii_Base\Dependencies\Symfony\Polyfill\Php80\PhpToken
    {
    }
}
