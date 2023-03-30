<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
    <script src="{{base-url}}/vendor/jquery-ui-1.13.2.custom/external/jquery/jquery.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/chota@latest">
    <link rel="stylesheet" href="{{base-url}}/app/styles/tailwind.css">
    <!-- <link rel="stylesheet" href="vendor/jquery-ui-1.13.2.custom/jquery-ui.min.css"> -->
    <title>Home</title>
</head>

<body>
    <nav class="sticky top-0 z-50 flex items-center h-24 w-screen min-h-full max-h-28 bg-slate-900">
        <div class="flex justify-between w-full mr-16 ml-16">
            <ul class="flex list-none m-0">
                <li class="mr-6">
                    <a class="text-white hover:text-red-300" href="{{base-url}}/spreadsheet">Home</a>
                </li>
                <li class="mr-6">
                    <a class="text-white hover:text-red-300" href="{{base-url}}/view/report">Graph</a>
                </li>
            </ul>
            <ul class="flex list-none m-0 float-right">
                <li class="mr-6">
                    <a class="text-white hover:text-red-300" href="{{base-url}}/devices">+ Device</a>
                </li>
            </ul>
        </div>
    </nav>

    <section id="mainbody" class="container w-screen h-full pr-6 pl-6 pt-6 pb-6 items-center justify-center">

        {{content}}

    </section>
</body>

</html>