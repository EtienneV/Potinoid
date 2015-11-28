<?php

  function rand_callout_color($couleur_precedente)
  {
    $i = 0;

    do
    {
      $i = rand(0, 5);
    } while($i == $couleur_precedente);

    if($i == 0)
    {
      $couleur['couleur'] = 'default';
      $couleur['i'] = $i;
    }
    else if($i == 1)
    {
      $couleur['couleur'] = 'primary';
      $couleur['i'] = $i;
    }
    else if($i == 2)
    {
      $couleur['couleur'] = 'success';
      $couleur['i'] = $i;
    }
    else if($i == 3)
    {
      $couleur['couleur'] = 'danger';
      $couleur['i'] = $i;
    }
    else if($i == 4)
    {
      $couleur['couleur'] = 'warning';
      $couleur['i'] = $i;
    }
    else
    {
      $couleur['couleur'] = 'info';
      $couleur['i'] = $i;
    }

    return $couleur;
  }

?>