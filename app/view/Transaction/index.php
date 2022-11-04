<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="../public/script/main.js"></script>
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

        <form id="viewTableForm" action="" method="POST">
            <table id="indexViewTable" border="1px" cellpadding="10px" cellspacing="0px">
                <tr>
                    <th>Tanggal</th>
                    <th>Category</th>
                    <th>Device</th>
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
                <?php $indexList = 1;
                foreach ((new Transaction)->showTransac() as $row) : ?>
                    <tr>
                        <td><?php print $row["dateTime"]; ?></td>
                        <td><?php print $row["nameCategory"]; ?></td>
                        <td><?php print $row["nameDevice"]; ?></td>
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
        new InPageFunct().toggleAction();
        new Table().refreshTable();
    </script>
</body>

</html>