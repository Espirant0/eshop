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

		foreach ($rules as $key => $rule) {
			$rules = $rule;

			foreach ($rules as $rule) {
				$rule = explode(':', $rule);

				$ruleName = $rule[0];
				$ruleValue = $rule[1] ?? null;

				$error = $this->validateRule($key, $ruleName, $ruleValue);

				if ($error) {
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

		switch ($ruleName) {
			case 'required':
				if (empty($value)) {
					return "Поле должно быть обязательным";
				}
				break;

			case 'min':
				if (mb_strlen($value) < $ruleValue) {
					return "Поле должно иметь минимум $ruleValue символа";
				}
				break;

			case 'max':
				if (mb_strlen($value) > $ruleValue) {
					return "Поле должно иметь не более $ruleValue символа";
				}
				break;
			case 'email':
				if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
					return "Поле должен быть корректный е-майл";
				}
				break;

			case 'confirmed':
				if ($value !== $this->data["{$key}_confirmation"]) {
					return "Field $key must be confirmed";
				}
				break;

			case 'alpha':
				if (!ctype_alpha($value)) {
					return "Field $key must contain only alphabetic characters";
				}
				break;

			case 'numeric':
				if (!ctype_digit($value)) {
					return "Поле должно содержать только числа";
				}
				break;

			case 'numeric_optional': // Новое правило поле может быть пустым,но иметь только числа
				if (!empty($value) && !ctype_digit($value)) {
					return "Поле должно содержать только числа";
				}
				break;

			case 'min_optional':
				if (!empty($value) && mb_strlen($value) < $ruleValue) {
					return "Поле должно иметь минимум $ruleValue символа";
				}
				break;
			case 'alpha_optional':
				if (!empty($value) && !ctype_alpha($value)) {
					return "Поле может содержать только буквы";
				}
				break;
		}

		return false;
	}

}


class Rules
{
	private array $rules = [];
	private array $currentRules = []; // Новое свойство для хранения текущих правил

	/**
	 * Добавляет правила валидации для указанных полей.
	 *
	 * @param array|string $fields Имя поля или массив имен полей
	 * @param array|string $rules Правила валидации или массив правил валидации
	 * @return $this
	 */
	public function addRule($fields, $rules): self
	{
		if (!is_array($fields)) {
			$fields = [$fields];
		}

		if (!is_array($rules)) {
			$rules = [$rules];
		}

		// Добавляем текущие правила в массив $currentRules
		foreach ($fields as $field) {
			foreach ($rules as $rule) {
				$this->currentRules[$field][] = $rule;
			}
		}

		// Добавляем текущие правила в массив $rules
		$this->rules = array_merge($this->rules, $this->currentRules);

		// Очищаем массив текущих правил для следующих добавлений
		$this->currentRules = [];

		return $this;
	}

	/**
	 * Получает все правила валидации для всех полей.
	 *
	 * @return array
	 */
	public function getRules(): array
	{
		return $this->rules;
	}
}