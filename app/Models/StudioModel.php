<?php namespace App\Models;

use CodeIgniter\Model;

class StudioModel extends Model
{
    protected $table = 'studios';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nama_studio', 'deskripsi', 'alamat', 'harga_per_jam', 'foto', 'status_aktif'];

    // Timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validasi
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    // Callbacks - Hapus 'array' di sini!
    protected $beforeInsert = [];
    protected $afterInsert  = [];
    protected $beforeUpdate = [];
    protected $afterUpdate  = [];
    protected $afterFind    = [];
    protected $afterDelete  = [];
}