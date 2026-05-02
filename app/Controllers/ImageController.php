<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ImageModel;

class ImageController extends ResourceController
{
    public function create()
    {
        $file = $this->request->getFile('image');
        $title = $this->request->getPost('title');

        // validasi input
        if (!$title) {
            return $this->failValidationErrors('Title wajib diisi');
        }

        if (!$file || !$file->isValid()) {
            return $this->fail('File tidak valid');
        }

        if (!$file->isImage()) {
            return $this->fail('File harus berupa gambar');
        }

        // generate nama file
        $newName = $file->getRandomName();

        // simpan ke public/uploads
        $file->move(FCPATH . 'uploads', $newName);

        // simpan ke database
        $model = new ImageModel();
        $model->save([
            'title' => $title,
            'filename' => $newName
        ]);

        return $this->respondCreated([
            'status' => 'success',
            'message' => 'Upload berhasil',
            'data' => [
                'title' => $title,
                'filename' => $newName,
                'url' => base_url('uploads/' . $newName)
            ]
        ]);
    }
}
