<?php

namespace App\Models;

use CodeIgniter\Model;

class MedidaModel extends Model
{
    protected $table            = 'medidas';
    protected $returnType       = 'App\Entities\Medida';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = ['nome','ativo','descricao'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'criado_em';
    protected $updatedField  = 'atualizado_em';
    protected $deletedField  = 'deletado_em';

    // Validation
    protected $validationRules = [
        'nome'     => 'required|max_length[120]|min_length[2]|is_unique[medidas.nome]',
    ];
    protected $validationMessages = [
        'nome' => [
            'required' => 'O campo Nome é Obrigatório.',
            'is_unique' => 'Essa Medida já Existe.',
        ],
    ];


    public function procurar($term) {
        if($term === null){
            return [];
        }

        return $this->select('id, nome')
                     ->like('nome', $term)
                     ->withDeleted(true)
                     ->get()
                     ->getResult();
    }

    public function desfazerExclusao(int $id){
        return $this->protect(false)->where('id',$id)
                                    ->set('deletado_em',null)
                                    ->update();
    }

    public function exibeValor(int $medida_id){
        return $this->select('medidas.nome')->selectMax('produtos_especificacoes.preco')
                    ->join('produtos_especificacoes','produtos_especificacoes.medida_id = medidas.id')
                    ->where('medidas.id',$medida_id)
                    ->where('medidas.ativo',true)
                    ->first();
    }

}

