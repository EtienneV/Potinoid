<div class="wrapper-nouveau-potin">
  <div id="fake-write-potin">
    <input type="text" id="fake-textarea-potin" placeholder="Ecris ton potin ici !">
  </div>
  
  
  <div class="wrapper-write-potin">
    <div class="selection-groupe-users">
      <span id="selection-groupe">Sur 

        <span class="item-user badge badge-success" iduser="' + ui.item.id_user + '"><?php echo $user_concerne['prenom'].' '.$user_concerne['nom'].' '; ?></span>, dans 

        <span class="groupe-selectionne" idgroupe="none"></span>
        <span class="form-selection-groupe">    
          <input type="text" id="recherche-groupe" placeholder="Cherche un groupe !" iduser="<?php echo $id_user; ?>">      
        </span>
      </span>
  
      <span class="selection-concernes" style="display : none"> avec 
        <span class="users-selectionnes"></span>
        <span class="form-selection-user">  
          <input type="text" id="recherche-user" placeholder="Quelqu'un de plus ?">
        </span>
      </span>
    </div>
  
    <div class="textarea-potin">
      <textarea class="textarea-potin-txt" rows="4" placeholder="Ecris ton potin ici"></textarea>
    </div>
    <div id="image_preview"><img id="previewing" src="" /></div>
    <div class="options-envoi-potin">
      <div class="wrapper-btn-nv-ptn">
  
        <span>
        <div class="fileUpload btn-nv-ptn btn-nv-ptn-photo">
          <span><span class="glyphicon glyphicon-camera" aria-hidden="true"></span> Photo</span>
  
          <form id="uploadimage" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_groupe" id="id_groupe" value="none"/>
            <input type="file" name="file" id="file" class="upload"/>
          </form>
        </div>
      </span>
        
  
        <div class="btn-nv-ptn btn-nv-ptn-envoyer"><span class="glyphicon glyphicon-send" aria-hidden="true"></span> Envoyer</div>
      </div>
    </div>
  </div>
</div>