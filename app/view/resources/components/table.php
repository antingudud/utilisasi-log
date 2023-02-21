<?php
$data = $this->params['content'];
$header = $this->params['header'];
$body = $this->params['body'];
?>
<!-- <section class="row" style="position: sticky; top: 0;">



</section> -->

<form id="viewTableForm" action="" method="POST">
    <table class="striped" id="indexViewTable" border="1px" cellpadding="10px" cellspacing="0px">
        <tr>
        <th colspan="9" style="background: white; position: sticky; top: 0;">
                <button class="button"><a href="/utilisasi-log/view/new">Upload data</a></button>
                <button class="button">Category</button>
                <select name="device" id="device">
                    <option hidden disabled selected value>Sort by</option>
                    <option>Date</option>
                </select>
                <button class="button" name="refreshViewIndex" id="refreshViewIndex">Refresh</button>
                <button class="button" name="alternateTableLook" id="alternateTableLook">Alternate Look</button>
                <button class="button" name="chartReport" id="chartReport"><a href="/utilisasi-log/view/report">Report</a></button>
            </th>
        </tr>
        <tr>
            <?php foreach ($header as $key => $value) : ?>
                <th style="background: white; position: sticky; top: 140px; box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);"><?php print_r($value) ?></th>
            <?php endforeach; ?>
        </tr>
        <?php foreach ($body as $key => $value) : ?>
            <tr>
                <?php foreach($value as $key => $row) : 
                    if ($key === 'idTrx') {continue;}?>
                    <td>
                        <?php print_r($row); ?>
                    </td>
                <?php endforeach; ?>
                <td>
                    <input value="<?php echo $value['idTrx'] ?>" type="checkbox"></input>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</form>

<script>
    $(document).ready(function(){
        $("#viewTableForm").submit(function(e) {
            e.preventDefault()

            if(e.originalEvent["submitter"]["id"] == "buttonViewUpdate")
            {
                let idValues = [];
                $.each($("input[type=checkbox]:checked"), function()
                {
                    idValues.push(this.value)
                })

                if(idValues.length > 0)
                {
                    console.log(`I am updating ${idValues.length} thing(s)`)
                    $.ajax(
                    {
                        url: '/utilisasi-log/submit/update',
                        type: 'POST',
                        data: {id: idValues},
                        cache: false,
                        success: function(response)
                        {
                            var lines = response.split('\n');
                            var newResponse = lines.slice(10).join('\n');
                          $('body').html(
                            newResponse
                            )
                        },
                        error: function(xhr, status, response)
                        {
                            console.log(response)
                        }
                    })
                }
            }
            if(e.originalEvent["submitter"]["id"] == "buttonViewDelete")
            {
                let idValues = []

                $.each($("input[type=checkbox]:checked"), function()
                {
                    idValues.push(this.value)
                })

                if(idValues.length > 0)
                {
                    console.log(`I am deleting ${idValues.length} thing(s)`)
                    $.ajax(
                    {
                        url: '/utilisasi-log/submit/delete',
                        type: 'POST',
                        data: {id: idValues},
                        cache: false,
                        success: function(response)
                        {
                            $('body').load('/utilisasi-log/view')
                        },
                        error: function(xhr, status, response)
                        {
                            console.log(response)
                        }
                    })
                }
            }
            if(e.originalEvent["submitter"]["id"] == "buttonSubmitEdit")
            {
                let idValues = []
                let download = []
                let upload = []

                for (let o = 0; i < $(":input[type=number][name='download']").length ; i++)
                {
                    download.push($(`#download${i}`).val());
                    upload.push($(`#upload${i}`).val());
                    idValues.push($(`#id${i}`).val());
                }

                $.ajax(
                {
                    url: '/utilisasi-log/submit/edit',
                    type: 'POST',
                    data: {id: idValues, download: download, upload: upload},
                    cache: false,
                    success: function(response)
                    {
                        console.log(response)
                    },
                    error: function(xhr, status, response)
                    {
                        console.log(response)
                    }
                })

            }
        })
        $("#alternateTableLook").click(function (e)
        {
            e.preventDefault()
            $.ajax(
                {
                    url: "/utilisasi-log/options/new",
                    type: "POST",
                    cache: false,
                    success: function(response)
                    {
                        let lines = response.split('\n');
                        let newResponse = lines.slice(12).join('\n');
                        $('body').html(
                            newResponse
                        )
                    },
                    error: function(xhr, status, response)
                    {
                        // respon = JSON.parse(response)
                        console.log(respon)
                    }
                }
            )
        })
    });
</script>