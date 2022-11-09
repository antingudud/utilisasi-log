<!DOCTYPE html>
<html lang="en">

<?php $ids = [1]; ?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-store" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
</head>

<body>
    <div id="editMenu">
        <h1>Edit</h1>


        <form action="" method="POST" id="editForm">
        <button onclick="$('#viewTableForm').load('/util/app/view/Transaction/index.php #indexViewTable')")>
            Return
        </button>
            <ul>
                <?php $array = json_decode($_POST['$_POST'], true) ;$indexList = 0 ;foreach ($array as $id) : ?>
                    <h4><?php print ($array[$indexList]['nameDevice'] . $array[$indexList]['nameCategory'])?></h4>
                    <p><?php print (date ( 'D, j M y', $array[$indexList]['UNIX_TIMESTAMP(dateTime)'] ) ) ?></p>
                    <input id="id<?php print($indexList) ?>" value = "<?php print ( $array[$indexList]['idTrx'] . substr(uniqid(), 0, 6) ) ?>" type="hidden" readonly></input>
                    <li>
                        <label for="download">download</label>
                        <input type="number" step="any" id="download<?php print($indexList) ?>" name="download" placeholder="<?php print $array[$indexList]['download'] ?>" required>
                    </li>
                    <li>
                        <label for="upload">upload</label>
                        <input type="number" step="any" id="upload<?php print($indexList) ?>" name="upload" placeholder="<?php print $array[$indexList]['upload'] ?>" required>
                    </li>
                <?php $indexList++; endforeach ?>
                        <button type="submit" name="submit" id="buttonSubmitEdit">Submit</button>
            </ul>
        </form>
    </div>
</body>

</html>