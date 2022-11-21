<div>
    <?php $rows = json_decode($_POST['$arrayData'], true);  ?>
    <form id="viewTableForm" action="" method="POST">
        <table id="indexViewTable" border="1px" cellpadding="10px" cellspacing="0px">
            <tr>
                <td rowspan = "2">Tanggal</td>
                <td colspan = "2">CR Indihome</td>
                <td colspan = "2">CP Indihome</td>
                <td colspan = "2">PK Biznet</td>
                <td colspan = "2">PK Indosat</td>
                <td colspan = "2">CK Orbit</td>
                <td colspan = "2">CK XL</td>
            </tr>
            <tr>
                <th>Download</th>
                <th>Upload</th>
                <th>Download</th>
                <th>Upload</th>
                <th>Download</th>
                <th>Upload</th>
                <th>Download</th>
                <th>Upload</th>
                <th>Download</th>
                <th>Upload</th>
                <th>Download</th>
                <th>Upload</th>
            </tr>
            <?php $indexList = 0;
            foreach ($rows as $row) : ?>
                <tr>
                    <td><?php print $row["dateCreated"]; ?></td>
                    <td><?php print $row["dl_CR_Indihome"]; ?></td>
                    <td><?php print $row["ul_CR_Indihome"]; ?></td>
                    <td><?php print $row["dl_CP_Indihome"]; ?></td>
                    <td><?php print $row["ul_CP_Indihome"]; ?></td>
                    <td><?php print $row["dl_PK_Biznet"]; ?></td>
                    <td><?php print $row["ul_PK_Biznet"]; ?></td>
                    <td><?php print $row["dl_PK_Indosat"]; ?></td>
                    <td><?php print $row["ul_PK_Indosat"]; ?></td>
                    <td><?php print $row["dl_CK_Orbit"]; ?></td>
                    <td><?php print $row["ul_CK_Orbit"]; ?></td>
                    <td><?php print $row["dl_CK_XL"]; ?></td>
                    <td><?php print $row["ul_CK_XL"]; ?></td>
                </tr>
            <?php $indexList++;
            endforeach ?>
        </table>
    </form>
</div>