<?php

namespace App\Models;

use CodeIgniter\Model;

class PedidosModel extends Model
{
    protected $table            = 'pedidos';
    protected $returnType       = 'App\Entities\Pedidos';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'usuario_id',
        'total',
        'slug',
        'status',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'criado_em';
    protected $updatedField  = 'atualizado_em';
    protected $deletedField  = 'deletado_em';

    // Validation
    protected $validationRules = [
        'usuario_id' => 'required|is_not_unique[usuarios.id]',
        'total'      => 'required|numeric',
        'status'     => 'required|string',
    ];
    protected $validationMessages = [
        'usuario_id' => [
            'required' => 'O campo usuário é obrigatório.',
            'is_not_unique' => 'O usuário não existe.',
        ],
        'total' => [
            'required' => 'O campo total é obrigatório.',
            'numeric' => 'O total deve ser um valor numérico.',
        ],
        'status' => [
            'required' => 'O campo status é obrigatório.',
            'string' => 'O status deve ser uma string.',
        ],
    ];

}
