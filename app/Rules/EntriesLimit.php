<?php

namespace App\Rules;

use App\Models\Meal;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EntriesLimit implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $meal = Meal::findOrFail($value);
        
        if ($meal->entries()->count() >= $meal->entries_limit) {
            $fail('The meal has reached its maximum number of entries.');
        }
    }
}
