<?php

namespace App\Model;

class Rule
{
	private array $rules = [];
	private array $currentRules = [];

	/**
	 * Добавляет правила валидации для указанных полей.
	 *
	 * @param array|string $fields Имя поля или массив имен полей
	 * @param array|string $rules Правила валидации или массив правил валидации
	 * @return $this
	 */
	public function addRule($fields, $rules): self
	{
		if (!is_array($fields))
		{
			$fields = [$fields];
		}

		if (!is_array($rules))
		{
			$rules = [$rules];
		}


		foreach ($fields as $field)
		{
			foreach ($rules as $rule)
			{
				$this->currentRules[$field][] = $rule;
			}
		}


		$this->rules = array_merge($this->rules, $this->currentRules);


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