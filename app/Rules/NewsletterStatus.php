<?php

namespace App\Rules;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\Rule;

class NewsletterStatus implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes ($attribute, $value)
    {
        return DB::table('newsletter_subscriptions')
                ->where('email', '=', $value)
                ->where('active', '=', 0)
                ->count() < 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O confirmare pentru abonare a fost deja trimisă. Verifică inclusiv secțiunea de spam sau contactează-ne pentru ajutor.';
    }
}
