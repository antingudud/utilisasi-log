<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload data</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/utilisasi-log/public/script/classes/New.js">
    </script>
</head>

<body>
    <div>
        <h1>KIP Upload Data Assessment Data</h1>
        <button><a href="../view">Home</a></button>
        <form action="" method="post" id="utilForm" required>
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
                        <option hidden disabled selected value> -- select an option -- </option>
                    </select>
                </li>
                <li>
                    <label for="date"></label>
                    <input type="date" name="date" id="date" value="<?php echo date("Y-m-d",time())?>">
                </li>
                <li>
                    <label for="download">download</label>
                    <input type="number" step="any" id="download" name="download" required>
                    <label for="download">MB</label>
                </li>
                <li>
                    <label for="upload">upload</label>
                    <input type="number" step="any" id="upload" name="upload" required>
                    <label for="upload">MB</label>
                </li>
                <li>
                    <button name="submit" id="submit">Submit</button>
                </li>
            </ul>
        </form>
    </div>



    <script>
        let ajax = new New();
        ajax.toggleCategory();
        ajax.formHandler();
    </script>
</body>

</html>