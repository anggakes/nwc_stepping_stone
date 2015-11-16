<?php
if (!empty($_POST)) {
    
    $qa = $_POST['a'];
    $qb = $_POST['b'];
    if ($qa > 10 || $qb > 10) {
        echo"Input Supply dan Demand Maks.10!!<br/>";
        echo"<a href='index.html'>Kembali</a>";
    } else {
        ?>

        <fieldset>
            <legend align="center">Input Nilai COST</legend>
            <form action="main.php" method="post">
                <table border='1' align="center">
                    <tr>
                        <td>Supply/Demand</td>
                        <?php
                        for ($w = 0; $w < $qb; $w++) {
                            echo"<td>Demand ".($w+1)."</td>";
                        }
                        ?>
                        <td>Q Supply</td>
                    </tr>
                    <?php
                    for ($i = 0; $i < $qa; $i++) {
                        echo "<tr><td> Supply .".($i+1)."</td>";

                        for ($j = 0; $j < $qb; $j++) {
                            ?>
                            <td><input type="number" min="1" required name="a[<?php echo $i ?>][<?php echo $j ?>]" value=""></td><?php if ($j == $qb-1) {
                                echo"<td><input type='number' required min='1' name='y[$i]'></td>";
                            } ?>
                            <?php } echo "</tr>";
                        } ?>	
                    <tr>
                        <td>Q Demand</td>
        <?php
        for ($d = 0; $d < $qb; $d++) {
            echo"<td><input type='number' min='1' required  name='x[$d]'></td>";
        }
        ?>
                    </tr>
                </table>
                <input type="hidden" name="o" value="<?php echo $qa ?>">		
                <input type="hidden" name="d" value="<?php echo $qb ?>">	
                <div align="center">	
                    <input type="submit" value="Submit" >
                </div>
            </form>
        </fieldset>
        <?php
    }
} else {
    header("location:index.html");
}
?>
<?php
// Gilang Abdul Aziz
// 0808549
// http://ltheordinary.blogspot.com
// 
// Special thanks to Rinaldi Guarsa
?>