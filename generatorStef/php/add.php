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


$add =  <<<EOL
    <div class="modal fade" id="{$name}Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                    <button class="btn btn-danger" type="button" data-dismiss="modal" v-on:click="{$name}_add()">Sauvegarder</button>
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
//echo htmlspecialchars($add);
//echo '</pre>';

file_put_contents("./files/{$name}/__add.html.twig", $add);
//file_put_contents("./files/{$name}/{$name}/__add.html.twig", $add);
mkdir('./files/templates/'.$name.'/', 0777, true);
mkdir('./files/_CONTROLLER/', 0777, true);
mkdir('./files/_NODEJS/', 0777, true);
mkdir('./files/_TEST/', 0777, true);
mkdir('./files/SERVICES/', 0777, true);
file_put_contents("./files/templates/{$name}/__add.html.twig", $add);

//echo "<code>".$var."</code>" ;