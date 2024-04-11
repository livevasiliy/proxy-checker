<?php declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;

class ProxyIpAddressRule implements ValidationRule, ValidatorAwareRule
{
    protected Validator $validator;

    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!Str::contains($value, ':')) {
            $fail('The :attribute must be a valid IP address.');
        }

    }

    public function setValidator(Validator $validator): static
    {
        $this->validator = $validator;

        return $this;
    }
}
