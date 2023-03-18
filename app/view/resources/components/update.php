<?php $device = $this->params['deviceList'] ?>
<div>
    <form action="{{base-url}}/submit/edit" method="POST" id="editForm">
        <button onclick="window.location = '{{base-url}}/view/'">
            Return
        </button>
        <ul>
            <?php $index = 0 ;foreach($device as $key => $value) : ?>
                <h4><?php  ?></h4>
                    <p><?php echo $value['date'] . ' ' .$value['nameCategory'] . ' ' . $value['nameDevice'] ?></p>
                    <input id="id<?php echo $index ?>" value = "<?php echo $value['idTrx'] ?>" type="hidden" readonly></input>
                    <li>
                        <label for="download">Download</label>
                        <input placeholder="<?php echo $value['download'] ?>" type="number" step="any" id="download<?php echo $index ?>" name="download" value="<?php echo $value['download'] ?>" required><p>MB</p>
                    </li>
                    <li>
                        <label for="upload">Upload</label>
                        <input placeholder="<?php echo $value['upload'] ?>" type="number" step="any" id="upload<?php echo $index ?>" name="upload" value="<?php echo $value['upload'] ?>" required><p>MB</p>
                    </li>
            <?php $index++; endforeach; ?>
            <button type="submit" id="submit_button">Submit</button>
        </ul>
    </form>
</div>

<script>
    $(document).ready(function() 
    {
        $('#editForm').submit(function(e) 
        {
            e.preventDefault();
            // response = $(this).serializeArray()
            // var result = response.map(element => element.reduce((acc, curr) => {
            //     acc[curr.name] = curr.value; 
            //     return acc;}, {}))
            // console.log(result)
            if (e.originalEvent["submitter"]["id"] == "submit_button") 
            {
                let idValues = [];
                let action = "submitEditEntry";
                let download = [];
                let upload = [];

                for (
                    let i = 0;
                    i < $(":input[type=number][name='download']").length;
                    i++
                ) {
                        download.push($(`#download${i}`).val());
                        upload.push($(`#upload${i}`).val());
                        idValues.push($(`#id${i}`).val());
                } 
                let data = download.map((downloadValue, index) => {
                    return [downloadValue, upload[index], idValues[index]];
                });
                let result = data.map(arr => ({
                    download: arr[0],
                    upload: arr[1],
                    idTrx: arr[2]
                }))

          request = $.ajax({
            url: "/utilisasi-log/submit/edit",
            type: "POST",
            data: {
                id: result
            },
            cache: false,
            success: function (response) {
              alert('Edit successfull');
              location.reload();
            },
            error: function(xhr, status, response) {
                        if(xhr.status === 400) {
                            var errorMessage = JSON.parse(xhr.responseText).error;
                            alert(`Error at updating: ${errorMessage}`);
                            return
                        } if(xhr.status === 500) {
                            var errorMessage = JSON.parse(xhr.responseText).parse;
                            alert(`Error at updating: ${errorMessage}`);
                            return
                        } else {
                            alert(`Error at updating: ${errorMessage}`);
                            return
                        }
                    }
            });
            }
        })
    })
</script>