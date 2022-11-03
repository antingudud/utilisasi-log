<!DOCTYPE html>
<?php
require($_SERVER['DOCUMENT_ROOT'] . "/util/include/include.php");
?>



<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AAA</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <h3>insert data</h3>
    <a href="../public">
        <p>Home</p>
    </a>
    <a href="view.php">
        <p>View</p>
    </a>

    <?php
    $a = new Device;
    $e = $a->showDeviceCategory("WAN");
    print_r($e);
    print("<br>" . realpath("classes/connection.php"));
    ?>
    <div id="asus"></div>

    <form action="" method="post" required>
        <ul>
            <li>
                <label for="category">Kategori</label><br>
                <input type="radio" name="category" value="LAN" id="lan">
                <label for="lan">LAN</label><br>
                <input type="radio" name="category" value="WAN" id="wan">
                <label for="wan">WAN</label>
            </li>
            <li>
                <label for="device">Device</label>
                <select name="device" id="device" required>
                    <?php $iteration = 0 ?>
                    <option hidden disabled selected value> -- select an option -- </option>
                </select>
            </li>
            <li>
                <label for="download">download</label>
                <input type="number" step="0.001" id="download" name="download" required>
            </li>
            <li>
                <label for="upload">upload</label>
                <input type="number" step="0.001" id="upload" name="upload" required>
            </li>
            <li>
                <button type="submit" name="submit">Submit</button>
            </li>
        </ul>
    </form>

                        <!-- Script -->

    <script>

        document.getElementById('lan').addEventListener('click',function(){
            console.log("SAY WHO");
            let xhr = new XMLHttpRequest();
            xhr.open('GET', '/util/php_util/insertLan.php');
            xhr.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    document.getElementById("device").innerHTML = this.responseText;
                }
            }

            xhr.send();
        })

        document.getElementById('wan').addEventListener('click',function(){
            console.log("SAY WHO");
            let xhr = new XMLHttpRequest();
            xhr.open('GET', '/util/php_util/insertWan.php');
            xhr.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    document.getElementById("device").innerHTML = this.responseText;
                }
            }

            xhr.send();
        })
    </script>

</body>

<script type="text/javascript" src="/util/javascript/main.js"></script>

</html>