<button class="btn btn-link btn-desinscription-gp">Se désinscrire du groupe</button>

<div class="desinscription-gp-wrapper" idGroupe="<?php echo $groupe['id_groupe']; ?>">
  <img class="chat-triste" src="images/chat_triste.jpg">
  <div class="des-text">
    <p>Es-tu sûr de vouloir quitter ce groupe ?<br>Tu n'auras plus accès au contenu de ce groupe, ni à tes potins, ni aux potins de tes amis ...<br><br>
      <span class="chat-des-text">De plus, ce petit chat sera très triste :(</span><br><br>
    </p><span class="suite-des-text">Mais si ta décision est prise, ... ainsi soit-il :'(</span><br>
    <div class="form-group">
    <input type="password" class="form-control" id="des-mdp" placeholder="Mot de passe">
  </div>
    <button class="btn btn-danger des-gp-quit-groupe">Quitter ce groupe</button>
    </div>
  
</div>