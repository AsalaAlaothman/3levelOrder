<?php

namespace App\Enums;

use Illuminate\Validation\Rules\Enum;

abstract class UserTypeEnum extends Enum {
    const Merchant = 0;
    const Customer = 1;
}
