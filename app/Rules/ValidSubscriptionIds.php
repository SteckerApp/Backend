<?php

namespace App\Rules;

use App\Models\Subscription;
use Illuminate\Contracts\Validation\Rule;
use App\Trait\HandleResponse;


class ValidSubscriptionIds implements Rule
{
    use HandleResponse;

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
    public function passes($attribute, $value)
    {
          // Convert the value to an array if it's not already
          $subscriptionIds = is_array($value) ? $value : [$value];

           // Check if all subscription IDs exist in the Subscription table
        return Subscription::whereIn('id', $subscriptionIds)->count() === count($subscriptionIds);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {

        return $this->errorResponse(null,'One or more subscription IDs are invalid.', 419);
    }
}
