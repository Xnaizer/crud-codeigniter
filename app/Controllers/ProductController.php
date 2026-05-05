<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ImageModel;

class ProductController extends ResourceController
{
    public function index(): string
    {
        return view('products');
    }

    public function create()
    {
        $file = $this->request->getFile('image');
        $title = $this->request->getPost('title');

        if (!$title) {
            return $this->fail('Title wajib diisi');
        }

        if (!$file || !$file->isValid()) {
            return $this->fail('File tidak valid');
        }

        if (!$file->isImage()) {
            return $this->fail('Harus gambar');
        }

        $newName = $file->getRandomName();
        $file->move(FCPATH . 'uploads', $newName);
        
        $model = new ImageModel();

        $model = new ImageModel();

        $id = $model->insert([
            'nama' => $title,
            'deskripsi' => $newName
        ]);

        dd($id);
        return $this->respondCreated([
            'status' => 'success'
        ]);
    }
}
