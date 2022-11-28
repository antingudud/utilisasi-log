<!DOCTYPE html>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/vendor/autoload.php';
$rows = (new Transaction)->showTransac();
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="../public/script/main.js"></script>
    <script src="../public/script/classes/Helper.js"></script>
    <title>Home</title>
</head>

<body>
    <div>
        <h1 id="greet">
            <script>
                let p = new Misc();
                document.getElementById('greet').innerHTML = `Good ${p.tellTime()}, our last hero!`
            </script>
        </h1>
        <button><a href="view/new">Upload data</a></button>
        <button>Category</button>
        <select name="device" id="device" required>
            <option hidden disabled selected value>Sort by</option>
            <option>Date</option>
        </select>
        <button name="refreshViewIndex" id="refreshViewIndex">Refresh</button>
        <button name="alternateTableLook" id="alternateTableLook">Alternate Look</button>

        <form id="viewTableForm" action="" method="POST">
            <table id="indexViewTable" border="1px" cellpadding="10px" cellspacing="0px">
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
                        <button type="submit" id="buttonViewDelete">Delete</button>
                    </th>
                </tr>
                <?php $indexList = 0;
                foreach ($rows as $row) : ?>
                    <tr>
                        <td><?php print date('D, j M y', $row['UNIX_TIMESTAMP(dateTime)'] ); ?></td>
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

    </div>

    <script>
        new InPageFunct().deleteUpdate("#viewTableForm");
        // new InPageFunct().toggleAction("#viewTableForm");
        tableFunct = new Table() ;
        tableFunct.refreshTable();
        tableFunct.changeLook();
    </script>
</body>

</html>