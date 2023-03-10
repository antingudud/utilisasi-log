<div>
        <h1>Add Device</h1>
        <button><a href="../view">Home</a></button>
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
    <script>
        $(document).ready( function() {
            $('#utilForm').submit(function(){
                event.preventDefault();
                var nameDevice = $('#device').val();
                var category = $('input:radio[name=category]:checked').val();
                
                $.ajax({
                    type: 'POST',
                    url: '/utilisasi-log/submit/device',
                    data: {
                        nameDevice: nameDevice,
                        category: category
                    },
                    success: function(response) {
                        $('#device').val('')
                    },
                    error: function(xhr, status, response) {
                        $('#device').val('')
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