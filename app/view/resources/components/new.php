<div>
        <h1>KIP Upload Data Assessment Data</h1>
        <button><a href="{{base-url}}/view">Home</a></button>
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
    <script type="module">
        import {  FormHandler } from "../../../../javascript/FormHandler.js";
        const formHandler = new FormHandler('utilForm', '{{base-url}}/submit/log');

        $(document).ready( function() {
            $('input:radio[name=category]').change(function() {
                var value = this.value;
                $.ajax({
                    type: 'POST',
                    url: '{{base-url}}/options/devices',
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
        });
    </script>