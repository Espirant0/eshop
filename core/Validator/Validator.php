<?php

namespace Core\Validator;

class Validator
{
	private array $errors = [];

	private array $data;

	public function validate(array $data, array $rules): bool
	{
		$this->errors = [];
		$this->data = $data;

		foreach ($rules as $key => $rule)
		{
			$rules = $rule;

			foreach ($rules as $rule)
			{
				$rule = explode(':', $rule);

				$ruleName = $rule[0];
				$ruleValue = $rule[1] ?? null;

				$error = $this->validateRule($key, $ruleName, $ruleValue);

				if ($error)
				{
					$this->errors[$key][] = $error;
				}
			}
		}

		return empty($this->errors);
	}

	public function errors(): array
	{
		return $this->errors;
	}

	private function validateRule(string $key, string $ruleName, string $ruleValue = null): string|false
	{
		$value = $this->data[$key];

		switch ($ruleName)
		{
			case 'required':
				if (empty($value))
				{
					return "Поле $key должно быть обязательным";
				}
				break;
			case 'min':
				if (mb_strlen($value) < $ruleValue)
				{
					return "Поле $key должно иметь минимум $ruleValue символа";
				}
				break;
			case 'max':
				if (mb_strlen($value) > $ruleValue)
				{
					return "Поле $key должно иметь не более $ruleValue символа";
				}
				break;
			case 'email':
				if (! filter_var($value, FILTER_VALIDATE_EMAIL))
				{
					return "В Поле $key должен быть корректный е-майл";
				}
				break;
			case 'confirmed':
				if ($value !== $this->data["{$key}_confirmation"])
				{
					return "Field $key must be confirmed";
				}
				break;
			case 'alpha':
				if (!ctype_alpha($value))
				{
					return "Field $key must contain only alphabetic characters";
				}
				break;
			case 'numeric':
				if (!ctype_digit($value))
				{
					return "Поле $key должно содержать только числа";
				}
				break;
		}

		return false;
	}
}
