<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Recaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $expected_action, string $ip_address)
    {
        $this->expected_action = $expected_action;
        $this->ip_address = $ip_address;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (app()->environment() === 'testing') {
            return true;
        }

        $recaptcha = new \ReCaptcha\ReCaptcha(env('RECAPTCHA_SECRET_KEY'));
        $resp = $recaptcha->setExpectedAction($this->expected_action)
            ->setScoreThreshold(0.5)
            ->verify($value, $this->ip_address);

        return $resp->isSuccess();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
