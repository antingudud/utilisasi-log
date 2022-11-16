<div>
    <?php json_decode($_POST['$_POST']); print_r ($_POST) ?>
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
                    <td><?php print date('D, j M y', $row['UNIX_TIMESTAMP(dateTime)']); ?></td>
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