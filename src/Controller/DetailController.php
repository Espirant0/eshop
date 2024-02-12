<?php

namespace App\Controller;

use Core\Database\Repo\CategoryListRepo;
use Core\Database\Repo\DetailRepo;

class DetailController extends BaseController
{
    public function showDetailPage($itemId): void
    {
        $categoryListRepo = new CategoryListRepo();

		$this->render('layout.php', [
<<<<<<< 0860605eb77d9a1f92f0faa650bc0811d5004397
			'content' => $this->render('DetailPage/detail.php', ['item' => DetailRepo::getBicycleListById($itemId[0])]),
=======
			'content' => $this->strRender('DetailPage/detail.php', [
				'item' => DetailRepo::getBicycleListById($itemId[0])
			]),
>>>>>>> bd049f1180f6e8147c7b52f44ba57dbe060d5fbe
            'categoryList' => $categoryListRepo::getCategoryList()
		]);
    }
}