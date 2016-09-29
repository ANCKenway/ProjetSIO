
<div class="top">
    <input type="text" id="recherche">
    <button id="rech" type="button"> Rechercher Club</button>

    <?php
    //var_dump($db->requete("Select * from livre"));
    foreach ($clubs as $club):
        ?>
        <div>
            <div>
                <h4><ins><?= $club->getNom(); ?></ins></h4> 
                <small><p><?= $club->getAdresse(); ?></p></small>  
                <a href="index.php?action=licences&club=<?= $club->getId(); ?>"> Licences du club </a> 
            </div>
        </div>
<?php endforeach; ?>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $("#rech").click(function () {
            location.href = "index.php?action=rechercher&nom=" + $("#recherche").val();
        });
    });
</script>


