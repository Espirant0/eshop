<?php

namespace Up\Controller;

class DetailController
{
    public function showDetailPage($id)
    {
        return 'Detail page' . $id;
    }
}