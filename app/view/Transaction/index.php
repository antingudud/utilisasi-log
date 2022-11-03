<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>

<body>
    <div>
        <h1>Good morning, our last hero!</h1>
        <button><a href="view/new">Upload data</a></button>
        <button>Category</button>
        <table border="1px" cellpadding="10px" cellspacing="0px">
            <tr>
                <th>Tanggal</th>
                <th>Category</th>
                <th>Device</th>
                <th>Download</th>
                <th>Upload</th>
                <th>Author</th>
                <th>Tanggal dibuat</th>
                <th>Tanggal diubah</th>
            </tr>
            <?php foreach ((new Transaction)->showTransac() as $row) : ?>
                <tr>
                    <td><?php print $row["dateTime"]; ?></td>
                    <td><?php print $row["nameCategory"]; ?></td>
                    <td><?php print $row["nameDevice"]; ?></td>
                    <td><?php print $row["download"]; ?></td>
                    <td><?php print $row["upload"]; ?></td>
                    <td><?php print $row["fullname"]; ?></td>
                    <td><?php print $row["dateCreated"]; ?></td>
                    <td><?php print $row["dateModified"]; ?></td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
</body>

</html>