<?php

namespace App\Controller;
use App\Service\DBHandler;

abstract class BaseController
{
	public function render(string $templateName, array $params): void
	{
		$template = __DIR__ . '/../View/' . $templateName;

		if (!file_exists($template))
		{
			http_response_code(404);
			include_once __DIR__ . '/../View/NotFoundPage/404.php';
			return;
		}
		extract($params);
		include_once $template;
	}
	public function strRender(string $templateName, array $params): ?string
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
		include_once $template;
		return ob_get_clean();
	}

	public function getPagesCount(int $itemsPerPage, string $table, ?array $filter):int
	{
		$DBOperator = new DBHandler();
		if($table === '')
		{
			return 0;
		}
		$queryAdditions= '';
		$tableList=$DBOperator->getResult("SELECT REFERENCED_TABLE_NAME
													FROM information_schema.KEY_COLUMN_USAGE
													WHERE TABLE_NAME = '$table' AND TABLE_SCHEMA = 'eshop' AND CONSTRAINT_NAME <> 'PRIMARY'");
		if (isset($filter['category'])) {
			$filterString = $filter['category'];
			$queryAdditions = "i INNER JOIN items_category ic on i.id = ic.item_id
        INNER JOIN category c2 on ic.category_id = c2.id WHERE c2.engName = '$filterString'";
		}
		$table = mysqli_real_escape_string($DBOperator,$table);
		$result = $DBOperator->query(
			"SELECT COUNT(*) AS count FROM $table $queryAdditions
					
		");
		return ceil($result->fetch_row()[0] / $itemsPerPage);
	}
}