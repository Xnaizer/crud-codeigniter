<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ImageModel;

class ImageController extends ResourceController
{
    public function index()
    {
        return $this->respond((new ImageModel())->findAll());
    }

    public function create()
    {
        $file = $this->request->getFile('image');
        $title = $this->request->getPost('title');

        if (!$title) return $this->fail('Title wajib diisi');
        if (!$file || !$file->isValid()) return $this->fail('File tidak valid');

        if ($file->getSize() > 2 * 1024 * 1024) {
            return $this->fail('Max 2MB');
        }

        $mime = $file->getMimeType();

        if (!in_array($mime, ['image/jpeg','image/png','image/gif','image/webp'])) {
            return $this->fail('Harus gambar');
        }

        $name = $file->getRandomName();
        $file->move(FCPATH . 'uploads', $name);

        (new ImageModel())->insert([
            'nama' => $title,
            'deskripsi' => $name
        ]);

        return $this->respondCreated(['status' => 'created']);
    }

    public function update($id = null)
    {
        $model = new ImageModel();
        $data = $model->find($id);

        if (!$data) return $this->failNotFound();

        $file = $this->request->getFile('image');
        $title = $this->request->getPost('title');

        $update = [];

        if ($title) $update['nama'] = $title;

        if ($file && $file->isValid()) {

            if ($file->getSize() > 2 * 1024 * 1024) {
                return $this->fail('Max 2MB');
            }

            $mime = $file->getMimeType();

            if (!in_array($mime, ['image/jpeg','image/png','image/gif','image/webp'])) {
                return $this->fail('Harus gambar');
            }

            $name = $file->getRandomName();
            $file->move(FCPATH . 'uploads', $name);

            $oldPath = FCPATH . 'uploads/' . $data['deskripsi'];
            if (file_exists($oldPath)) unlink($oldPath);

            $update['deskripsi'] = $name;
        }

        if (empty($update)) return $this->fail('Tidak ada perubahan');

        $model->update($id, $update);

        return $this->respond(['status' => 'updated']);
    }

    public function delete($id = null)
    {
        $model = new ImageModel();
        $data = $model->find($id);

        if (!$data) return $this->failNotFound();

        $path = FCPATH . 'uploads/' . $data['deskripsi'];
        if (file_exists($path)) unlink($path);

        $model->delete($id);

        return $this->respond(['status' => 'deleted']);
    }

    public function deleteAll()
    {
        $model = new ImageModel();

        foreach ($model->findAll() as $d) {
            $path = FCPATH . 'uploads/' . $d['deskripsi'];
            if (file_exists($path)) unlink($path);
        }

        $model->truncate();

        return $this->respond(['status' => 'all deleted']);
    }
}