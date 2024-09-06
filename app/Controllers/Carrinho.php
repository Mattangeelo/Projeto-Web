<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Carrinho extends BaseController
{
    private $validacao;
    private $produtoEspecificacaoModel;
    private $extraModel;
    private $produtoModel;
    private $medidaModel;
    private $acao;
    public function __construct()
    {   
        $this->validacao = service('validation');
        $this->produtoEspecificacaoModel = new \App\Models\ProdutoEspecificacaoModel();
        $this->extraModel = new \App\Models\ExtraModel();
        $this->produtoModel = new \App\Models\ProdutoModel();
        $this->medidaModel = new \App\Models\MedidaModel();
        $this->acao = service('router')->methodName();
    }
    public function index()
    {
        //
    }

    public function adicionar(){
        if($this->request->getMethod() === 'post'){
            $produtoPost = $this->request->getPost('produto');
            $this->validacao->setRules([
                'produto.slug' => ['label' => 'Produto','rules' => 'required|string'],
                'produto.especificacao_id' => ['label' => 'Valor','rules' => 'required|greater_than[0]'],
                'produto.preco' => ['label' => 'Valor do produto','rules' => 'required|greater_than[0]'],
                'produto.quantidade' => ['label' => 'Quantidade','rules' => 'required|greater_than[0]'],
            ]);

            if(!$this->validacao->withRequest($this->request)->run()){
                return redirect()->back()
                    ->with('errors_model', $this->validacao->getErrors())
                    ->with('atencao','Por favor verifique os erros abaixo e tente novamente')
                    ->withInput();
            }

            /**Validamos a existencia da especificacao_id */

            $especificacaoProduto = $this->produtoEspecificacaoModel
                                         ->join('medidas','medidas.id = produtos_especificacoes.medida_id')
                                         ->where('produtos_especificacoes.id',$produtoPost['especificacao_id'])
                                         ->first();
            if($especificacaoProduto == null){
                return redirect()->back()
                    ->with('fraude','Não conseguimos encontrar a sua Solitação. Por favor,entre em contato com a nossa equipe e informe o código de erro ERRO-ADD-PROD-1001'); // FRAUDE no form
            }
            /**Caso o extra_id venha no post,validamos a existencia no banco de dados */
            if($produtoPost['extra_id'] && $produtoPost['extra_id'] != ""){
                $extra = $this->extraModel->where('id',$produtoPost['extra_id'])->first();
                if($extra == null){
                    return redirect()->back()
                        ->with('fraude','Não conseguimos encontrar a sua Solitação. Por favor,entre em contato com a nossa equipe e informe o código de erro ERRO-ADD-PROD-2002'); // FRAUDE no form
                }
            }

            /**Validamos a existencia do produto */

            $produto = $this->produtoModel->select(['id','nome','slug','ativo'])
                                          ->where('slug',$produtoPost['slug'])->first()->toArray();
            if($produto == null || $produto['ativo'] == false){
                return redirect()->back()
                    ->with('fraude','Não conseguimos encontrar a sua Solitação. Por favor,entre em contato com a nossa equipe e informe o código de erro ERRO-ADD-PROD-3003'); // FRAUDE no form
            }
            
            /**Criamos o slug composto para identificar a existencia ou não do item no carrinho */
            $produto['slug'] = mb_url_title($produto['slug'].'-'.$especificacaoProduto->nome.'-'.(isset($extra)?'com extra-'.$extra->nome : ''),'-',true);

            $produto['nome'] = $produto['nome'].' '.$especificacaoProduto->nome.' '.(isset($extra)?'Com extra '.$extra->nome : '');

            /**Definimos o preco,quantidade e tamanho do produto */
            $preco = $especificacaoProduto->preco + (isset($extra)? $extra->preco : 0);

            $produto['preco'] = number_format($preco,2);
            $produto ['quantidade'] = (int) $produtoPost['quantidade'];
            $produto['tamanho'] = $especificacaoProduto->nome;

            unset($produto['ativo']);

            if(session()->has('carrinho')){
                /**Existe um carrinho de compras */
                $produtos = session()->get('carrinho');
                
                $produtosSlugs = array_column($produtos,'slug');

                if(in_array($produto['slug'],$produtosSlugs)){
                    /** Ja existe o produto no carrinho, incrementamos a quantidade */
                    $produtos = $this->atualizaProduto($this->acao,$produto['slug'],$produto['quantidade'],$produtos);

                    session()->set('carrinho',$produtos);

                }else{
                    /** Não existe no carrinho */
                    /** Push adiciona na sessao carrinho um array com o produto. */
                    session()->push('carrinho',[$produto]);
                }
            }else{  
                /* Não existe ainda um carrinho de compras na sessão*/
                $produtos[] = $produto;
                
                session()->set('carrinho',$produtos);
            }
             return redirect()->back()->with('sucesso','Produto adicionado com sucesso!');

        }else{
            return redirect()->back();
        }
    }

    public function especial(){
        if($this->request->getMethod() === 'post'){
            $produtoPost = $this->request->getPost();
            $this->validacao->setRules([
                'primeira_metade' => ['label' => 'Primeiro produto','rules' => 'required|greater_than[0]'],
                'segunda_metade' => ['label' => 'Segundo produto','rules' => 'required|greater_than[0]'],
                'tamanho' => ['label' => 'Tamanho produto','rules' => 'required|greater_than[0]'],
            ]);

            if(!$this->validacao->withRequest($this->request)->run()){
                return redirect()->back()
                    ->with('errors_model', $this->validacao->getErrors())
                    ->with('atencao','Por favor verifique os erros abaixo e tente novamente')
                    ->withInput();
            }
            $primeiroProduto = $this->produtoModel->select(['id','nome','slug'])->where('id',$produtoPost['primeira_metade'])->first();
            if($primeiroProduto == null){
                return redirect()->back()
                    ->with('fraude','Não conseguimos encontrar a sua Solitação. Por favor,entre em contato com a nossa equipe e informe o código de erro ERRO-ADD-PROD-3003'); // FRAUDE no form
            }
            $segundoProduto = $this->produtoModel->select(['id','nome','slug'])->where('id',$produtoPost['segunda_metade'])->first();
            if($segundoProduto == null){
                return redirect()->back()
                    ->with('fraude','Não conseguimos encontrar a sua Solitação. Por favor,entre em contato com a nossa equipe e informe o código de erro ERRO-ADD-PROD-3003'); // FRAUDE no form
            }

            $primeiroProduto = $primeiroProduto->toArray();
            $segundoProduto = $segundoProduto->toArray();
            /**Caso o extra_id venha no post,validamos a existencia no banco de dados */
            if($produtoPost['extra_id'] && $produtoPost['extra_id'] != ""){
                $extra = $this->extraModel->where('id',$produtoPost['extra_id'])->first();
                if($extra == null){
                    return redirect()->back()
                        ->with('fraude','Não conseguimos encontrar a sua Solitação. Por favor,entre em contato com a nossa equipe e informe o código de erro ERRO-ADD-PROD-2002'); // FRAUDE no form
                }
            }

            $medida = $this->medidaModel->exibeValor($produtoPost['tamanho']);

            if($medida->preco == null){
                return redirect()->back()
                ->with('fraude','Não conseguimos encontrar a sua Solitação. Por favor,entre em contato com a nossa equipe e informe o código de erro ERRO-ADD-PROD-2002'); // FRAUDE no form
            }
            /**Criamos o slug composto para identificar a existencia ou não do item no carrinho */
            $produto['slug'] = mb_url_title($medida->nome .'-metade-'.$primeiroProduto['slug'].'-metade-'.$segundoProduto['slug'].'-'.(isset($extra)?'com extra-'.$extra->nome : ''),'-',true);
            
            $produto['nome'] = $medida->nome .' metade '.$primeiroProduto['nome'].' metade '.$segundoProduto['nome'].' '.(isset($extra)?'com extra '.$extra->nome : '');
            $preco = $medida->preco + (isset($extra)? $extra->preco : 0);

            $produto['preco'] = number_format($preco,2);
            $produto ['quantidade'] = 1;
            $produto['tamanho'] = $medida->nome;
            if(session()->has('carrinho')){
                /**Existe um carrinho de compras */
                $produtos = session()->get('carrinho');
                
                $produtosSlugs = array_column($produtos,'slug');

                if(in_array($produto['slug'],$produtosSlugs)){
                    /** Ja existe o produto no carrinho, incrementamos a quantidade */
                    $produtos = $this->atualizaProduto($this->acao,$produto['slug'],$produto['quantidade'],$produtos);

                    session()->set('carrinho',$produtos);

                }else{
                    /** Não existe no carrinho */
                    /** Push adiciona na sessao carrinho um array com o produto. */
                    session()->push('carrinho',[$produto]);
                }
            }else{  
                /* Não existe ainda um carrinho de compras na sessão*/
                $produtos[] = $produto;
                
                session()->set('carrinho',$produtos);
            }
             return redirect()->back()->with('sucesso','Produto adicionado com sucesso!');
        }else{
            return redirect()->back();
        }
    }

    private function atualizaProduto(string $acao,string $slug,int $quantidade,array $produtos){
        $produtos = array_map(function($linha) use ($acao,$slug,$quantidade){
            if($linha['slug'] == $slug){
                if($acao === 'adicionar'){
                    $linha['quantidade'] += $quantidade;
                }
                if($acao === 'atualizar'){
                    $linha['quantidade'] = $quantidade;
                }
            }
            return $linha;
        },$produtos);

        return $produtos;
    }
}
