<?php echo $this->extend('layout/principal_web'); ?>

<?php echo $this->section('titulo'); ?> 
    <?php echo esc($titulo); ?> 
<?php echo $this->endSection(); ?>

<?php echo $this->section('estilos'); ?>
    <link rel="stylesheet" href="<?php echo site_url('web/src/assets/css/produto.css'); ?>"/>
<?php echo $this->endSection(); ?>

<?php echo $this->section('conteudo'); ?>

<div class="container section" id="menu" data-aos="fade-up" style="margin-top: 3em">
    <div class="col-sm-8 col-md-offset-2">
        <!-- product -->
        <div class="product-content product-wrap clearfix product-deatil">
            <div class="row">
                <h2 class="name">
                    <?php echo esc($titulo); ?>
                </h2>

                <?php echo form_open('carrinho/especial'); ?>

                <div class="row" style="min-height: 300px !important;">
                    <div class="col-md-12" style="margin-bottom: 2em">
                        <?php if(session()->has('errors_model')): ?>
                            <ul style="margin-left: -1.6em !important; list-style: decimal;">
                                <?php foreach(session('errors_model') as $error): ?>
                                    <li class="text-danger"><?php echo esc($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6" style="min-height: 300px !important;">
                        <div id="imagemPrimeiroProduto" style = "margin-bottom: 1em">
                            <img class="img-responsive center-block d-block mx-auto" src="<?php echo site_url("web/src/assets/img/pizza.jpg"); ?>" alt="Escolha o Produto" width="200">
                        </div>
                        <label>Escolha a primeira metade do produto</label>
                        <select id="primeira_metade" class="form-control" name="primeira_metade">
                            <option value="">Escolha seu produto</option>
                            <?php foreach($opcoes as $opcao): ?>
                                <option value="<?php echo $opcao->id; ?>"><?php echo esc($opcao->nome); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6" style="min-height: 300px !important;">
                        <div id="imagemSegundoProduto" style = "margin-bottom: 1em">
                            <img class="img-responsive center-block d-block mx-auto" src="<?php echo site_url("web/src/assets/img/pizza.jpg"); ?>" alt="Escolha o Produto" width="200">
                        </div>
                        <label>Escolha a Segunda Metade</label>
                        <select id="segunda_metade" class="form-control" name="segunda_metade">
                            <!-- Será apresentado as opções para segunda metade -->
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>Tamanho do Produto</label>
                        <select id="tamanho" class="form-control" name="tamanho">
                            <!-- Será apresentado as opções para tamanho -->
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div id="boxInfoExtras" style = "display:none">
                            <label class="small">Extras</label>
                            <div class="radio">
                                <label>
                                    <input type = "radio" class="extras" name="extra" checked="">Sem Extra
                                </label>
                            </div>
                            <div id="extras">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div id="valor_produto_customizado">

                    </div>
                </div>
                <div>
                    <input type="text" id="extra_id" name="extra_id" placeholder="extra_id_hidden">
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <input id="btn-adiciona" type="submit" class="btn btn-success" value="Adicionar" style="margin-top: 20px;">
                    </div>
                    <div class="col-sm-3">
                        <a href="<?php echo site_url("produto/detalhes/$produto->slug"); ?>" class="btn btn-info" style="margin-top: 20px;">Voltar</a>
                    </div>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
        <!-- end product -->
    </div> 
</div>

<?php echo $this->endSection(); ?>

<?php echo $this->section('scripts'); ?>
<script>
    $(document).ready(function(){
        $("#btn-adiciona").prop("disabled", true);
        $("#btn-adiciona").prop("value","Selecione um Tamanho");

        $("#primeira_metade").on('change', function() {

            var primeira_metade = $(this).val();
            var categoria_id = '<?php echo $produto->categoria_id; ?>';

            $("#imagemPrimeiroProduto").html('<img class="img-responsive center-block d-block mx-auto" src="<?php echo site_url("web/src/assets/img/pizza.jpg"); ?>" alt="Escolha o Produto" width="200">');
            if(primeira_metade){
                $.ajax({
                    type:'get',
                    url:'<?php echo site_url('produto/procurar'); ?>',
                    dataType: 'json',
                    data:{
                        primeira_metade: primeira_metade,
                        categoria_id: categoria_id,
                    },
                    beforeSend: function(data){
                        $("#segunda_metade").html(''); 
                    },
                    success: function(data){
                        if(data.imagemPrimeiroProduto){
                            $("#imagemPrimeiroProduto").html('<img class="img-responsive center-block d-block mx-auto" src="<?php echo site_url("produto/imagem/"); ?>'+data.imagemPrimeiroProduto+'" alt="Escolha o Produto" width="200">');
                        }

                        if(data.produtos){
                            $("#segunda_metade").html('<option>Escolha a segunda metade</option>');

                            $(data.produtos).each( function(){
                                var option = $('<option/>');
                                option.attr('value',this.id).text(this.nome);
                                $("#segunda_metade").append(option);
                            });

                        }else{
                            $("#segunda_metade").html('<option>Não encontramos opções de customização</option>');
                        }
                    },
                });
            }else{
                /** Cliente não escolheu a primeira_metade */
                $("#segunda_metade").html('<option>Escolha a primeira metade</option>');
            }
        });

        $("#segunda_metade").on('change',function(){
            var primeiro_produto_id = $("#primeira_metade").val();
            var segundo_produto_id = $(this).val();
            $("#imagemSegundoProduto").html('<img class="img-responsive center-block d-block mx-auto" src="<?php echo site_url("web/src/assets/img/pizza.jpg"); ?>" alt="Escolha o Produto" width="200">');
            $("#boxInfoExtras").hide();
            $("#extras").html('');
            if(primeiro_produto_id && segundo_produto_id){
                $.ajax({
                    type:'get',
                    url:'<?php echo site_url('produto/exibeTamanhos'); ?>',
                    dataType: 'json',
                    data:{
                        primeiro_produto_id: primeiro_produto_id,
                        segundo_produto_id: segundo_produto_id,
                    },
                    beforeSend: function(data){
                        
                    },
                    success: function(data){
                        if(data.imagemSegundoProduto){
                            $("#imagemSegundoProduto").html('<img class="img-responsive center-block d-block mx-auto" src="<?php echo site_url("produto/imagem/"); ?>'+data.imagemSegundoProduto+'" alt="Escolha o Produto" width="200">');
                        }

                        if(data.medidas){
                            $("#tamanho").html('<option>Escolha o tamanho</option>');

                            $(data.medidas).each( function(){
                                var option = $('<option/>');
                                option.attr('value',this.id).text(this.nome);
                                $("#tamanho").append(option);
                            });

                        }else{
                            $("#tamanho").html('<option>Escolha a segunda metade do Produto</option>');
                        }

                        if(data.extras){
                            $("#boxInfoExtras").show();
                            $(data.extras).each( function(){
                                var input = "<div class='radio'><label><input type='radio' class='extra' name='extra' data-extra='"+this.id+"' value='"+this.preco+"' >"+this.nome+"</label></div>"
                                $("#extras").append(input);
                            });

                            $(".extra").on('click',function(){
                                var extra_id = $(this).attr('data-extra');
                                $("#extra_id").val(extra_id);
                            });

                        }
                    },
                });
            }
        });
    });
</script>
<?php echo $this->endSection(); ?>
