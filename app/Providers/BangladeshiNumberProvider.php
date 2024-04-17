<?php

namespace Database\Seeders\Providers;

use Faker\Provider\Base;

class BangladeshiPhoneNumberProvider extends Base
{
    /**
     * Generate a Bangladeshi mobile phone number.
     *
     * @return string
     */
    public function bangladeshiMobileNumber()
    {
        $prefixes = ['017', '018', '019', '015', '016'];
        $prefix = $this->randomElement($prefixes);

        return $prefix . $this->numberBetween(10000000, 99999999);
    }
}
