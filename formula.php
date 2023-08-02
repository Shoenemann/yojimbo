<?php

# Istruzioni: chiama la funzione yojimbo_table (alla fine del file)

function probability_daigoru($p) {
    $punteggio = floor($p);
    if ($punteggio >= 32) {
        return 0;
    } else {
        return (32-$punteggio)/64;
    }
}

function probability_kozuka($p) {
    $punteggio = floor($p);
    if ($punteggio >= 48) {
        return 0;
    } elseif($punteggio >=32) {
        return (48-$punteggio)/64;
    } else {
        return (48-32)/64;
    }
}

function probability_wakizashi($p) {
    $punteggio = floor($p);
    if ($punteggio >= 64) {
        return 0;
    } elseif($punteggio >=48) {
        return (64-$punteggio)/64;
    } else {
        return (64-48)/64;
    }
}

function probability_wakizashi_all($r) {
    $wa = 100;
    $wa -= $r["daigoro"];
    $wa -= $r["kozuka"];
    $wa -= $r["wakizashi"];
    $wa -= $r["zanmato"];
    return $wa;
    // $punteggio = floor($p);
    // if ($punteggio >= 80) {
    //     return 0;
    // } elseif($punteggio >=64) {
    //     return (80-$punteggio)/64;
    // } elseif($punteggio >= 16) {
    //     return (80-64)/64;
    // } else {
    //     return ($punteggio)/64;
    // }
}

function probability_zanmato($p) {
    $punteggio = floor($p);
    if ($punteggio >= 80) {
        return 1;
    } elseif($punteggio >=16) {
        return (64 + $punteggio-80)/64;
    } else {
        return 0;
    }
}


function motivation_from_guil($guil) {
    $r = 0;
    for($i = 4;$i<536870912*2; $i *=2) {
        if ($guil < $i) {
            return $r;
        }
        $r +=4;
    } 
    return $r;
}

function average_training($r) {
    $t = 0;
    $t -= 1 * $r["daigoro"]/100;
    $t += 1 * $r["wakizashi"]/100;
    $t += 3 * $r["wakizashi_all"]/100;
    $t += 4 * $r["zanmato"]/100;
    return $t;
}

function row_probability_NTSC($punteggio,$guil) {
    $r = [
        "guil_min" => $guil,
        "motivation" => motivation_from_guil($guil)/2,
        "daigoro" => probability_daigoru($punteggio)*100,
        "kozuka" => probability_kozuka($punteggio)*100,
        "wakizashi" => probability_wakizashi($punteggio)*100,
        "zanmato" => probability_zanmato($punteggio)*100
    ];
    $r["wakizashi_all"] = probability_wakizashi_all($r);
    $r["average_compatibility_increment"]  = average_training($r);
    return $r;
}

function row_probability_PAL($punteggio,$guil, $punteggio_ricalcolato) {
    # nelle versioni PAL se Zanmato non viene eseguito, allora si ricalcola tutto 
    # ponendo il livello Zanmato del mostro uguale a 1 
    $pnz = 1 - probability_zanmato($punteggio);
    $r = [
        "guil_min" => $guil,
        "motivation" => motivation_from_guil($guil),
        "daigoro" => $pnz * probability_daigoru($punteggio)*100,
        "kozuka" => $pnz * probability_kozuka($punteggio)*100,
        "wakizashi" => $pnz * probability_wakizashi($punteggio)*100,
        "zanmato" => probability_zanmato($punteggio)*100
    ];
    $r["wakizashi_all"] = probability_wakizashi_all($r);
    $r["average_compatibility_increment"]  = average_training($r);
    return $r;
}


function yojimbo_free_attack($aff,$zan) {
    # nel caso di free attack la formula dei punti e' molto semplice
    $punti = $aff/4;
    # Yojimbo non fa free zanmato se il livello del mostro e' almeno 2
    $r = row_probability_NTSC($punti,0);
    if ($zan > 1) {
        $r["wakizashi_all"] += $r["zanmato"];
        $r["zanmato"] = 0;
    }  
    return $r;  
}


function yojimbo_normal_attack( #
    $aff = 255, #affinita con yojimbo
    $ver = 1, #versione gioco (0 = japanese, 1= remastered)
    $guil = 1, # guil pagati a yojimbo
    $percent_guil = 0.5, # %guil totali pagati
    $zan = 1, #livello zanmato del mostro
    $cho = 3, # initial choice (1= invocatore, 2= mostri, 3=forti)
    $turbo = false # overdrive
    ) 
{
    # $ver= 1 (versione PAL e HD remastered)
    # $ver = 0 (versione NTSC e Japanese)

    # motivazione (basic motivation 0-112)
    $BM = motivation_from_guil($guil);
    $p_mot = ($ver == 1?  $BM       :   $BM/2);
    # affinita con Yuna (compatibility 0-255) 
    $p_aff = ($ver == 1?  $aff/10   :   $aff/30);
    # sacrificio: solo se hai scelto "addestrami come invocatrice" il calcolo e' diverso
    $p_sac = 1;
    if($cho == 1){
        $p_sac = 0.75 + 0.5 * $percent_guil;
    }
    # livello zanmato: se hai scelto "nemici forti" il calcolo e' diverso
    $p_zan = 1/$zan;
    if ($cho == 3) {
        if ($zan <= 3) {
            $p_zan = 0.81;
        } else {
            $p_zan = 0.4;
        }
    }
    # turbo / overdrive (0-20)
    $p_turbo = 0;
    if ($turbo && $ver == 1) {
        $p_turbo = 20;
    } elseif ($turbo && $ver == 0) {
        $p_turbo = 2;
    }

    # ecco il calcolo del punteggio finale
    $punti = ($p_mot + $p_aff) * $p_sac * $p_zan + $p_turbo;

    if ($ver == 0) {
        $r = row_probability_NTSC($punti,$guil);
    }
    else {
        $punti_senza_zanmato = ($p_mot + $p_aff) * $p_sac + $p_turbo;
        $r = row_probability_PAL($punti,$guil,$punti_senza_zanmato);
    }

    
    return $r;
}


function yojimbo_table  (#
    $aff = 255, #affinita con yojimbo
    $ver = 1, #versione gioco (0 = japanese, 1= remastered)
    $percent_guil = 0.5, # %guil dei totali pagati (ha senso solo con $cho = 1)
    $zan = 1, #livello zanmato del mostro
    $cho = 3, # initial choice (1= invocatore, 2= mostri, 3=forti)
    $turbo = false # overdrive
    )  {
    $table = [];
    $atk = "normal"; # questa tabella e' per gli attacchi normali quando paghi guil a yojimbo
    # una riga per ogni ammontare di guil pagati
    for($guilmax = 4;$guilmax<536870912*2; $guilmax *=2) {
        if($guilmax == 4) {
            $guil_min=1;
        } else {
            $guil_min = $guilmax / 2;
        }
        $row = yojimbo_normal_attack($aff,$ver,$guil_min,$percent_guil,$zan,$cho,$turbo);
        array_push($table,$row);
    } 
    # ultima riga 
    $row = yojimbo_normal_attack($aff,$ver,536870912,$percent_guil,$zan,$cho,$turbo);
    array_push($table,$row);
    return $table;
}


?>
