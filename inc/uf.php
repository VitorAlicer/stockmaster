<!-- Cria um elemento <select> com classe "form-control" e id dinâmico -->
<select class="form-control" id="<?=$uf_id?>" name="uf">
   <option value="">Selecione um Estado</option> <!-- Opção padrão solicitando ao usuário que selecione um estado -->
   <?php
   // Itera sobre a lista de estados retornada pela função getUF()
   foreach (getUF() as $key => $value) {
      $selected = ""; // Inicializa a variável para armazenar a opção selecionada
      // Verifica se a chave do estado atual é igual à selecionada ou se o modo é "new" e o estado é "RS"
      if($key == $uf_selected || ($mode == "new" && $key == "RS")) {
         $selected = "selected"; // Define a opção como selecionada
      }
      // Mostra a opção no <select> com valor da chave e nome do estado em maiúsculas
      echo '<option value="'.$key.'" '.$selected.'>'.mb_strtoupper($value).'</option>';
   }
   ?>
</select>
