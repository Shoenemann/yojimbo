<?php
  
include 'formula.php';
# per la funzione yojimbo_table(...)

#le Get sono: mode,livellomostro,turbo,affinita


$aff = $_GET["affinita"];   #affinita con yojimbo (0-255)
$ver = $_GET["versione"];   #versione gioco (0 = NTSC e Japanese, 1= PAL e HD remastered)
# %guil dei totali pagati (ha senso solo con $cho = 1) (0.0-1.0)
$percent_guil = $_GET["percent_guil"]/100 ?? 0.5;        
$zan = $_GET["livellomostro"]; #livello zanmato del mostro (da 1 a 5 nel NTSC, da 1 a 6 in PAL)
$cho = $_GET["choice"];       # initial choice (1= invocatore, 2= mostri, 3=forti)
$turbo = $_GET["turbo"] ?? false;    # overdrive (true/false)

$table = yojimbo_table($aff,$ver,$percent_guil,$zan,$cho,$turbo);

echo json_encode($table);

/*

echo json_encode($affin);

#print_r($table);
*/
?>

