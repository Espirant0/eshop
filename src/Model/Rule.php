<?php

namespace App\Model;

class Rule
{
	private array $rules = [];
	private array $currentRules = [];

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

	public function getRules(): array
	{
		return $this->rules;
	}
}