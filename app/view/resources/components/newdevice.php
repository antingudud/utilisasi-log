<div>
        <h1>Add Device</h1>
        <button><a href="{{base-url}}/view">Home</a></button>
        <form action="" method="post" id="utilForm" required>
            <ul>
                <li>
                    <label for="category">Kategori</label><br>
                    <input required type="radio" name="category" value="LAN" id="LAN">
                    <label for="lan">LAN</label><br>
                    <input type="radio" name="category" value="WAN" id="WAN">
                    <label for="wan">WAN</label>
                </li>
                <li>
                    <label for="device">Nama Device</label>
                    <input required type="text" name="device" id="device">
                </li>
                <li>
                    <button name="submit" id="submit">Submit</button>
                </li>
            </ul>
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script type="module">
        import {  FormHandler } from "{{base-url}}/javascript/FormHandler.js";
        const formHandler = new FormHandler('utilForm', '{{base-url}}/submit/device');
    </script>