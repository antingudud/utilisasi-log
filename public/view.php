<!DOCTYPE html>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/vendor/autoload.php';


$e = new Transaction;
$devRow = $e->showTransac();

?>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction</title>
</head>

<body>
    <h1>helwo worls rhis si transcation</h1>


    <a href="../public">A</a>
    <a href="insert.php">C</a><br>
    <?php $e = new TransactionView;
    print_r($e->showTransac()); ?>
    <table border="1px" cellpadding="10px" cellspacing="0px">
        <tr>
            <th>Tanggal</th>
            <th>Category</th>
            <th>Device</th>
            <th>Upload</th>
            <th>Download</th>
            <th>Author</th>
            <th>Tanggal dibuat</th>
            <th>Tanggal diubah</th>
        </tr>
        <?php foreach ($devRow as $row) : ?>
            <tr>
                <td><?php print $row["dateTime"]; ?></td>
                <td><?php print $row["nameCategory"]; ?></td>
                <td><?php print $row["nameDevice"]; ?></td>
                <td><?php print $row["upload"]; ?></td>
                <td><?php print $row["download"]; ?></td>
                <td><?php print $row["fullname"]; ?></td>
                <td><?php print $row["dateCreated"]; ?></td>
                <td><?php print $row["dateModified"]; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

</body>

</html>