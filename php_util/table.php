<?php
?>
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