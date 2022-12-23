<div>
        <h1>KIP Upload Data Assessment Data</h1>
        <button><a href="../view">Home</a></button>
        <form action="" method="post" id="utilForm" required>
            <ul>
                <li>
                    <label for="category">Kategori</label><br>
                    <input type="radio" name="category" value="LAN" id="lan">
                    <label for="lan">LAN</label><br>
                    <input type="radio" name="category" value="WAN" id="wan">
                    <label for="wan">WAN</label>
                </li>
                <li>
                    <label for="device">Device</label>
                    <select name="device" id="device" required>
                        <option hidden disabled selected value> -- select an option -- </option>
                    </select>
                </li>
                <li>
                    <label for="date"></label>
                    <input type="date" name="date" id="date" value="<?php echo date("Y-m-d",time())?>">
                </li>
                <li>
                    <label for="download">download</label>
                    <input type="number" step="any" id="download" name="download" required>
                    <label for="download">MB</label>
                </li>
                <li>
                    <label for="upload">upload</label>
                    <input type="number" step="any" id="upload" name="upload" required>
                    <label for="upload">MB</label>
                </li>
                <li>
                    <button name="submit" id="submit">Submit</button>
                </li>
            </ul>
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script>
        $(document).ready( function() {
            $('input:radio[name=category]').change(function() {
                var value = this.value;
                $.ajax({
                    type: 'POST',
                    url: '/utilisasi-log/options/devices',
                    data: {
                        category: value
                    },
                    success: function(response){
                        response = JSON.parse(response)
                        $('#device').empty()
                        $('#device').append('<option hidden disabled selected value> -- select an option -- </option>')
                        for (var i = 0; i < response.length; i++) {
                            $('#device').append('<option value="' + response[i].idDevice + '">' + response[i].nameDevice + '</option>');
                        }
                        // console.log(response)
                    }
                })
            })

            $('#utilForm').submit(function(){
                event.preventDefault();
                var idDevice = $('#device').val()
                var date = $('#date').val()
                var download = $('#download').val()
                var upload = $('#upload').val()
                
                $.ajax({
                    type: 'POST',
                    url: '/utilisasi-log/submit/log',
                    data: {
                        idDevice: idDevice,
                        date: date,
                        download: download,
                        upload: upload
                    },
                    success: function(response) {
                        console.log(response)
                    },
                    error: function(xhr, status, response) {
                        if(xhr.status === 400) {
                            var errorMessage = JSON.parse(xhr.responseText).error;
                            console.error(errorMessage)
                        } if(xhr.status === 500) {
                            var errorMessage = JSON.parse(xhr.responseText).parse;
                            console.error(errorMessage)
                        } else {
                            console.error(`Error: ${xhr.status}`);
                        }
                    }
                }
                )
            })
        }       );
    </script>