<!DOCTYPE html>
<?php
require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

$Transact = new Transaction;
$rows = $Transact->getMainTable();
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/utilisasi-log/public/script/main.js"></script>
    <script src="/utilisasi-log/public/script/classes/Helper.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/chota@latest">
    <title>Home</title>
</head>

<body>
    <section class="container col">

        <h1 id="greet">
            <script>
                let p = new Misc();
                document.getElementById('greet').innerHTML = `Good ${p.tellTime()}, our last hero!`
            </script>
        </h1>

        <section class="row">

            <button class="button"><a href="view/new">Upload data</a></button>
            <button class="button">Category</button>
            <select name="device" id="device" required>
                <option hidden disabled selected value>Sort by</option>
                <option>Date</option>
            </select>
            <button class="button" name="refreshViewIndex" id="refreshViewIndex">Refresh</button>
            <button class="button" name="alternateTableLook" id="alternateTableLook">Alternate Look</button>
            <button class="button" name="chartReport" id="chartReport"><a href="view/report">Report</a></button>

        </section>

        <section class="is-center">

            <form id="viewTableForm" action="" method="POST">
                <table class="striped" id="indexViewTable" border="1px" cellpadding="10px" cellspacing="0px">
                    <tr>
                        <th>Tanggal</th>
                        <th>Interface</th>
                        <th>Download</th>
                        <th>Upload</th>
                        <th>Author</th>
                        <th>Tanggal dibuat</th>
                        <th>Tanggal diubah</th>
                        <th>
                            <button type="submit" id="buttonViewUpdate">Update</button>
                            <button class="button error" type="submit" id="buttonViewDelete">Delete</button>
                        </th>
                    </tr>
                    <?php $indexList = 0;
                    foreach ($rows as $row) : ?>
                        <tr>
                            <td><?php print $row['date']; ?></td>
                            <td><?php print $row["nameCategory"] . " " . $row["nameDevice"]; ?></td>
                            <td><?php print $row["download"]; ?></td>
                            <td><?php print $row["upload"]; ?></td>
                            <td><?php print $row["fullname"]; ?></td>
                            <td><?php print $row["dateCreated"]; ?></td>
                            <td><?php print $row["dateModified"]; ?></td>
                            <td><?php  ?><input type="checkbox" id="<?php print $indexList ?>" value="<?php print $row['idTrx'] ?>"></td>
                        </tr>
                    <?php $indexList++;
                    endforeach ?>
                </table>
            </form>


        </section>
    </section class="container col">

    <script>
        new InPageFunct().deleteUpdate("#viewTableForm");
        // new InPageFunct().toggleAction("#viewTableForm");
        tableFunct = new Table();
        tableFunct.refreshTable();
        tableFunct.changeLook();
    </script>
</body>

</html>