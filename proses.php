<?php

if (!empty($_POST)) {
    $total = 0;
    $count = 0;
    $totala = 0;
    $totalr = 0;
    $totalall = 0;
    $selisih = 0;
    $dummya = 0;
    $dummyr = 0;
    $a = $_POST['y'];
    $r = $_POST['x'];
    $xa = $_POST['y'];
    $xr = $_POST['x'];
    $o = $_POST['o'];
    $d = $_POST['d'];
    $c = $_POST['a'];
    $x = array();
    $i = 1;
    $j = 1;


//menghitung total destination
    for ($l = 1; $l <= $d; $l++) {
        $totalr = $totalr + $xr[$l];
    }

//menghitung total origin
    for ($l = 1; $l <= $o; $l++) {
        $totala = $totala + $xa[$l];
    }
//pengecekan jika terjadi dummy
    if ($totala > $totalr) {
        $totalall = $totala;
        $selisih = $totala - $totalr;
        $d = $d + 1;
        $xr[$d] = $selisih;
        $r[$d] = $selisih;
        //status dummy
        $dummyr = 1;
        for ($f = 1; $f <= $o; $f++) {
            //set value dummy
            $c[$f][$d] = 0;
        }
    } else if ($totala < $totalr) {
        $totalall = $totalr;
        $selisih = $totalr - $totala;
        $o = $o + 1;
        $xa[$o] = $selisih;
        $a[$o] = $selisih;
        //status dummy
        $dummya = 1;
        for ($f = 1; $f <= $d; $f++) {
            //set value dummy
            $c[$o][$f] = 0;
        }
    } else {
        $totalall = $totalr;
    }
//melakukan iterasi untuk menentukan cost
    do {
        if ($a[$i] < $r[$j]) {
            $x[$i][$j] = $a[$i];
            $r[$j] = $r[$j] - $a[$i];
            $a[$i] = 0;
            $i++;
            $count++;
        } else if ($a[$i] > $r[$j]) {
            $x[$i][$j] = $r[$j];
            $a[$i] = $a[$i] - $r[$j];
            $r[$j] = 0;
            $j++;
            $count++;
        } else {
            $x[$i][$j] = $a[$i];
            $a[$i] = 0;
            $r[$j] = 0;
            $i++;
            $j++;
            $count++;
        }
    } while ($count <= $o + $d - 1);
    echo "<fieldset>";
    echo "<legend align='center'>Tabel Optimalisasi dengan Metode NWC</legend>";
    echo "<table border='1' align='center'>";
    echo"<tr>
<td>Supply/Demand</td>";
    for ($w = 1; $w <= $d; $w++) {
        if ($w == $d && $dummyr == 1) {
            echo"<td>dummy</td>";
        } else {
            echo"<td>Demand $w</td>";
        }
    }
    echo"<td>Q Supply</td>";
    echo"</tr>";
    for ($i = 1; $i <= $o; $i++) {
        echo"<tr>";
        if ($i == $o && $dummya == 1) {
            echo"<td>dummy</td>";
        } else {
            echo"<td>Supply $i</td>";
        }
        for ($j = 1; $j <= $d; $j++) {

            if ($x[$i][$j] > 0) {
                echo"<td><table ><tr ><td  rowspan='2' width='50'>" . $x[$i][$j] . '</td><td style="border-left:1px solid;border-bottom:1px solid;" >' . $c[$i][$j] . "</td></tr><tr><td>&nbsp;</td></tr></table></td>";
            } else {
                echo"<td><table ><tr ><td  rowspan='2' width='50'>0</td><td style='border-left:1px solid;border-bottom:1px solid;width:auto;'>" . $c[$i][$j] . "</td></tr><tr><td>&nbsp;</td></tr></table></td>";
            }
            if ($j == $d) {
                echo"<td>" . $xa[$i] . "</td>";
            }
        }

        echo"</tr>";
    }
    echo"<tr>
<td>Q Demand</td>";
    for ($l = 1; $l <= $d; $l++) {
        echo"<td>" . $xr[$l] . "</td>";
    }
    echo"<td>$totalall</td>";
    echo"</tr>";
    echo "</table>";
    echo "</fieldset>";
    echo"<fieldset>";
    echo "<legend align='center'>Hasil Optimalisasi</legend>";
    echo"<div align='center'>";
    for ($i = 1; $i <= $o; $i++) {
        for ($j = 1; $j <= $d; $j++) {
            if ($x[$i][$j] > 0 && $c[$i][$j] > 0) {
                echo"Supply $i x Demand $j =&nbsp;" . $c[$i][$j] . "&nbsp;x&nbsp;" . $x[$i][$j] . "&nbsp;=&nbsp;" . $c[$i][$j] * $x[$i][$j] . "&nbsp;";
                echo "<br />";
            }
        }
    }
//menghitung optimal cost

    for ($i = 1; $i <= $o; $i++) {
        for ($j = 1; $j <= $d; $j++)
            $total = $total + ($c[$i][$j] * $x[$i][$j]);
    }
    echo "Total Cost :&nbsp;Rp" . number_format($total);

    echo"<br /><a href='index.html'>Ulangi</a>";
    echo"</div>";
    echo"</fieldset>";
} else {
    header("location:index.php");
}
?>

<?php
// Gilang Abdul Aziz
// 0808549
// http://ltheordinary.blogspot.com
// 
// Special thanks to Rinaldi Guarsa
?>