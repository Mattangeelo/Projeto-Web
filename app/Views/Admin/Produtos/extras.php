<?php echo $this->extend('Admin/layout/principal'); ?>


<?php echo $this->section('titulo'); ?>
<?php echo $titulo ?>
<?php echo $this->endSection(); ?>




<?php echo $this->section('estilos'); ?>
    <link rel="stylesheet" href="<?php echo site_url('admin/vendors/select2/select2.min.css');?>"/>

<?php echo $this->endSection(); ?>





<?php echo $this->section('conteudo'); ?>
<div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-header bg-secondary pb-0 pt-4">
                <h4 class="card-title text-white"><?php echo esc($titulo); ?></h4>
            </div>

            <div class="card-body">

            <?php if(session()->has('errors_model')): ?>

                <ul>
                    <?php foreach(session('errors_model') as $error): ?>
                        <li class="text-danger"><?php echo $error ?></li>
                    <?php endforeach; ?>
                </ul>
            
            <?php endif; ?>
                
            <?php echo form_open("admin/produtos/cadastrarExtras/$produto->id");?>

                <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Escolha um extra do produto (opcional)</label>
                            <select class="form-control js-example-basic-single"  name="extra_id">
                                <option value="">Escolha um adicional</option>

                                <?php foreach($extras as $extra): ?>
                                    <option value="<?php echo $extra->id ?>"><?php echo esc($extra->nome); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                </div>

                <button type="submit" class="btn btn-secondary mr-2 btn-sm">
                    <i class="mdi mdi-checkbox-marked-circle btn-icon-prepend"></i>
                        Inserir
                </button>
                
                <a href= "<?php echo site_url("admin/produtos/show/$produto->id"); ?>"class="btn btn-light text-dark btn-sm">
                    <i class="mdi mdi mdi-keyboard-return btn-icon-prepend"></i>
                         Voltar
                </a>
                   
            <?php echo form_close();?>
            <hr class="mt-5 mb-3">
            <div class="form-row">
            
                <div class="col-md-12 mt-5">

                    <?php if(empty($produtosExtras)): ?>
                        <p>Esse produto não tem extras</p>
                    <?php else: ?>
                        <h4 class="card-title">Extras</h4>
                    <p class="card-description">
                        <code>Extras ja adicionados</code>
                    </p>
                    <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>Extra</th>
                          <th>Preço</th>
                          <th class="text-center">Remover</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach($produtosExtras as $extraProduto):?>
                        <tr>
                          <td><?php echo esc($extraProduto->extra); ?></td>
                          <td>R$&nbsp;<?php echo esc(number_format($extraProduto->preco,2)); ?></td>
                            <td class="text-center">
                                <?php echo form_open("admin/produtos/excluirExtra/$extraProduto->id/$extraProduto->produto_id"); ?>
                                <button type="submit" class="btn badge badge-danger">X</button>
                                <?php echo form_close(); ?>
                            </td>
                        </tr>

                        <?php endforeach; ?>
                        
                      </tbody>
                    </table>
                   
                    <div class="mt-3">
                        <?php echo $pager->links() ?>
                    </div>

                  </div>
                    <?php endif;?>

                
                </div>
            </div>
        </div>
    </div>

</div>

<?php echo $this->endSection(); ?>





<?php echo $this->section('scripts'); ?>

<script src="<?php echo site_url('admin/vendors/mask/jquery.mask.min.js');?>"></script>
<script src="<?php echo site_url('admin/vendors/mask/app.js');?>"></script>

<script src="<?php echo site_url('admin/vendors/select2/select2.min.js');?>"></script>

<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2({
            placeholder:'Digite o nome do extra',
            allowClear:false,
            "language":{
                "noResults": function(){
                    return "Extra não encontrado&nbsp;&nbsp;<a class='btn btn-secondary btn-sm' href='<?php echo site_url('admin/extras/criar'); ?>'>Cadastrar</a>";
                }
            },
            escapeMarkup: function(markup){
                return markup;
            }
        });
    });
</script>

<?php echo $this->endSection(); ?>