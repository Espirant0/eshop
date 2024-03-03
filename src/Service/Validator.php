<?php

namespace App\Service;

class Validator
{
	private array $errors = [];

	private array $data;

	private array $filteredRules;

	public function validate(array $data, array $rules): bool
	{
		$this->errors = [];
		$this->data = $data;
		$this->filteredRules = array_intersect_key($rules, $data);

		foreach ($this->filteredRules as $key => $rule)
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
					return "Поле $key должно иметь минимум $ruleValue символов";
				}
				break;

			case 'max':
				if (mb_strlen($value) > $ruleValue)
				{
					return "Поле $key должно иметь не более $ruleValue символов";
				}
				break;
			case 'email':
				if (!filter_var($value, FILTER_VALIDATE_EMAIL))
				{
					return "В поле $key должен быть корректный е-майл";
				}
				break;
			case 'date':
				if (!preg_match("/\\d{4}-\\d{2}-\\d{2}/", $value))
				{
					return "В поле $key должна быть корректная дата";
				}
				break;
			case 'confirmed':
				if ($value !== $this->data["{$key}_confirmation"])
				{
					return "Поле $key должно быть подтверждено";
				}
				break;

			case 'alpha':
				if (!ctype_alpha($value))
				{
					return "Поле $key должно содержать только буквы";
				}
				break;

			case 'numeric':
				if (!ctype_digit($value))
				{
					return "Поле $key должно содержать только числа";
				}
				break;

			case 'numeric_optional':
				if (!empty($value) && !ctype_digit($value))
				{
					return "Поле $key должно содержать только числа";
				}
				break;

			case 'min_optional':
				if (!empty($value) && mb_strlen($value) < $ruleValue)
				{
					return "Поле $key должно иметь минимум $ruleValue символов";
				}
				break;

			case 'alpha_optional':
				if (!empty($value) && !ctype_alpha($value))
				{
					return "Поле $key может содержать только буквы";
				}
				break;

			case 'max_optional':
				if (!empty($value) && mb_strlen($value) > $ruleValue)
				{
					return "Поле $key должно иметь не более $ruleValue символов";
				}
				break;
		}
		return false;
	}
}


