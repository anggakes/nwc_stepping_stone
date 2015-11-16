<html>
    <head><title>NWC in PHP</title></head>
    <body>
        <fieldset>
            <legend align="center">Applikasi NWC menggunakan PHP</legend>
            <p align="center">Optimalisasi Produksi menggunakan North West Corner</p>
        </fieldset>
        <form action="input.php"  method="post">
            <div align="center" >
                <fieldset>
                    <legend align="center">Input Supply dan Demand</legend>
                    <table>
                        <tr>
                            <td>Supply</td><td>:</td><td><input type="number" name="a" min="1" max="" required></td>
                        </tr>
                        <tr>
                            <td>Demand</td><td>:</td><td><input type="number" name="b" min="1" max="" required></td>
                        </tr>
                        <tr>
                            <td colspan="3" align="center"><input type="submit" value="Submit"></td>
                        </tr>
                        </tr>
                </fieldset>
            </div>
        </form>	
    </body>
</html>
<?php
// Gilang Abdul Aziz
// 0808549
// http://ltheordinary.blogspot.com
// 
// Special thanks to Rinaldi Guarsa
?>