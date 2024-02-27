<?php

namespace App\Controller;

use App\Service\DBHandler;

abstract class BaseController
{
	public function render(string $templateName, array $params): string
	{
		$template = __DIR__ . '/../View/' . $templateName;

		if (!file_exists($template))
		{
			http_response_code(404);

			ob_start();
			include_once __DIR__ . '/../View/NotFoundPage/404.php';
			return ob_get_clean();
		}

		extract($params);

		ob_start();
		require $template;
		return ob_get_clean();
	}

	public function getPagesCount(int $itemsPerPage, string $table): int
	{
		$DBOperator = DBHandler::getInstance();
		if ($table === '')
		{
			return 0;
		}
		$table = mysqli_real_escape_string($DBOperator, $table);
		$result = $DBOperator->query(
			"SELECT COUNT(*) AS count FROM $table 
					
		");
		return ceil($result->fetch_row()[0] / $itemsPerPage);
	}
}