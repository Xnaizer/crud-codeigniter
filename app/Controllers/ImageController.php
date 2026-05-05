<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ImageModel;

class ImageController extends ResourceController
{
     public function create()
    {
        try {
            $file = $this->request->getFile('image');
            $title = $this->request->getPost('title');

            if (!$title) {
                return $this->fail('Title wajib diisi');
            }

            if (!$file || !$file->isValid()) {
                return $this->fail('File tidak valid');
            }

            $mime = $file->getMimeType();

            if (!in_array($mime, ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'])) {
                return $this->fail('File harus berupa gambar');
            }

            $newName = $file->getRandomName();

            if (!$file->hasMoved()) {
                $file->move(FCPATH . 'uploads', $newName);
            }

            $model = new \App\Models\ImageModel();

            $model->insert([
                'nama' => $title,
                'deskripsi' => $newName
            ]);

            return $this->respondCreated([
                'status' => 'success',
                'message' => 'Upload berhasil',
                'data' => [
                    'nama' => $title,
                    'deskripsi' => $newName,
                    'url' => base_url('uploads/' . $newName)
                ]
            ]);

        } catch (\Throwable $e) {
            return $this->fail($e->getMessage());
        }
    }
    public function index()
    {
        $model = new \App\Models\ImageModel();
        $data = $model->findAll();

        return $this->respond($data);
    }

    public function delete($id = null)
    {
        $model = new \App\Models\ImageModel();
        $data = $model->find($id);

        if (!$data) {
            return $this->failNotFound('Data tidak ditemukan');
        }

        // hapus file
        $filePath = FCPATH . 'uploads/' . $data['deskripsi'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $model->delete($id);

        return $this->respond([
            'status' => 'success',
            'message' => 'Data berhasil dihapus'
        ]);
    }

    public function update($id = null)
    {
        $model = new \App\Models\ImageModel();
        $data = $model->find($id);

        if (!$data) {
            return $this->failNotFound('Data tidak ditemukan');
        }

        $file = $this->request->getFile('image');
        $title = $this->request->getPost('title');

        $updateData = [];

        if ($title) {
            $updateData['nama'] = $title;
        }

        if ($file && $file->isValid()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads', $newName);

            // hapus file lama
            $oldPath = FCPATH . 'uploads/' . $data['deskripsi'];
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }

            $updateData['deskripsi'] = $newName;
        }

        $model->update($id, $updateData);

        return $this->respond([
            'status' => 'success',
            'message' => 'Data berhasil diupdate'
        ]);
    }
}
