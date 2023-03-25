<?php

$inputs = '';
//$data['titre'] ?? null
foreach ($var as $item){
    $inputs = $inputs. "<div class='form-group col-6'>
                           <label>$item</label>
                           <input class='form-control' type='text' v-model='$name.$item'>
                      </div> 
                      <br>
                      "
    ;
}


$update =  <<<EOL
    <div class="modal fade" id="{$name}UpdateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-danger" role="{$name}">
        <div class="modal-content">

            <form method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <br>
                            <div class="card">

                                <div class="card-header">
                                    <strong>Ajouter un $name</strong>
                                </div>

                                <div class="card-body">

                                    <div class="row">

                                        $inputs

                                        <div class="form-group col-12" style="padding: 30px">
                                            <myupload :width="400" :height="300" v-model="$name.image"></myupload>
                                            <br><br>
                                        </div>

                                    </div>

                                </div>

                                <div class="card-footer">
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Fermer</button>
                                    <button class="btn btn-danger" type="button" data-dismiss="modal" v-on:click="{$name}_update()">Modifier</button>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>
            </form>

        </div>

    </div>
</div>

EOL;


//echo '<pre>';
//    echo htmlspecialchars($update);
//echo '</pre>';

file_put_contents("./files/{$name}/__update.html.twig", $update);
//file_put_contents("./files/{$name}/{$name}/__update.html.twig", $update);
file_put_contents("./files/templates/{$name}/__update.html.twig", $update);

//echo "<code>".$var."</code>" ;