<?php echo $this->extend('layout/principal_web'); ?>


<?php echo $this->section('titulo'); ?> <?php echo $titulo ?> <?php echo $this->endSection(); ?>




<?php echo $this->section('estilos'); ?>

<<link rel="stylesheet" href="<?php echo site_url("web/src/assets/css/produto.css"); ?>"/>

<?php echo $this->endSection(); ?>





<?php echo $this->section('conteudo'); ?>
            
<?php echo $this->endSection(); ?>





<?php echo $this->section('scripts'); ?>

<!-- Aqui enviamos para o template principal os scripts -->
<?php echo $this->endSection(); ?>
        
