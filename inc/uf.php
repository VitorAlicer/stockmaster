<select class="form-control" id="<?=$uf_id?>" name="uf">
   <option value="">Selecione um Estado</option>
   <?php
   foreach (getUF() as $key => $value){
      $selected = "";
      if($key == $uf_selected || ($mode == "new" && $key == "RS")) $selected = "selected";
      echo '<option value="'.$key.'" '.$selected.'>'.mb_strtoupper($value).'</option>';
   }
   ?>
</select>