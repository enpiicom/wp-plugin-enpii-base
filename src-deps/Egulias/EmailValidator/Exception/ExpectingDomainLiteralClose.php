<?php

namespace Enpii_Base\Deps\Egulias\EmailValidator\Exception;

class ExpectingDomainLiteralClose extends InvalidEmail
{
    const CODE = 137;
    const REASON = "Closing bracket ']' for domain literal not found";
}
