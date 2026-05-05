<?php

namespace App\Controllers;

use App\Models\ImageModel;

class ProductController extends BaseController
{
    public function index()
    {
        return view('product_view');
    }

    public function detail(int $id) // tambahin type biar warning hilang
    {
        $model = new ImageModel();
        $row = $model->find($id);

        if (!$row) {
            return redirect()->to('/products');
        }

        return view('product_detail', ['data' => $row]);
    }
}