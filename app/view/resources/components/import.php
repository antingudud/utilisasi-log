<form method="POST" action="{{baseUrl}}/submit/import" enctype="multipart/form-data">
    <label for="uploadfile">Upload xls spreadsheet</label>
    <input type="file" name="uploadfile" id="uploadfile" required>

    <label for="mode">Mode</label><br>
    <input type="radio" name="mode" id="wan" required><label for="wan">WAN</label><br>
    <input type="radio" name="mode" id="lan"><label for="lan">LAN</label><br>

    <button id="import" type="submit">import</button>
</form>

<script>
    // $(document).ready(function ()
    // {
    //     $("#import").click(function (e)
    //     {
    //         e.preventDefault()
    //         if(confirm("Are you sure?"))
    //         {
    //             alert("Yes")
    //         } else
    //         {
    //             alert("No")
    //         }
    //     })
    // })
</script>