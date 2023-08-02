<?php

function random_row() {
    $r =    [
        "guil_min" => rand(1,1000),
        "motivation" => rand(1,56),
        "daigoro" => rand(0,100)/10,
        "kozuka" => rand(0,100)/10,
        "wakizashi" => rand(0,100)/10,
        "wakizashi_all" => rand(0,100)/10,
        "zanmato" => rand(0,100)/10
        ];
    $r["zanmato"] = 100-$r["daigoro"] - $r["kozuka"] - $r["wakizashi"] - $r["wakizashi_all"];
    return $r;
}

#print_r(random_row());
/*  $atk = "normal", #free attack / normal attack
    $aff = 255, #affinita con yojimbo
    $ver = 1, #versione gioco (0 = japanese, 1= remastered)
    $guil = 1, # guil pagati a yojimbo
    $percent_guil = 0.5, # %guil totali pagati
    $zan = 1, #livello zanmato del mostro
    $cho = 3, # initial choice (1= invocatore, 2= mostri, 3=forti)
    $turbo = false # overdrive
    ) */
//
?>
