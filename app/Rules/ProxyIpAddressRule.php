<?php declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
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
        [$ipAddress, $port] = explode(':', $value);
        if (!$this->validator->validateIp('ipAddress', $ipAddress)) {
            $fail('Invalid IP address.');
        } elseif (!is_numeric($port)) {
            $fail('Invalid port.');
        }
    }

    public function setValidator(Validator $validator): static
    {
        $this->validator = $validator;

        return $this;
    }
}
