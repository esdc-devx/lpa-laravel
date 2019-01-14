<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        <link href="/css/infosheet.css" rel="stylesheet" >
        <link href="/css/infosheet.print.css" rel="stylesheet" media="print">
    </head>
    <body>
        <header>
            <h1><i class="el-icon el-icon-lpa-owl"></i> @yield('title')</h1>
            <div class="date">
                {{ now() }}
            </div>
            <div class="print">
                <a href="#"><i class="el-icon-printer"></i></a>
            </div>
        </header>
        <div class="container">
            @yield('content')
        </div>
        <script>
            // Print handler.
            document.querySelector('.print a').onclick = function() {
                window.print();
                return false;
            };

            // Visibility toggle forms boxes.
            const toggleButtons = document.querySelectorAll('.el-card__header .toggle');
            for (let i = 0; i < toggleButtons.length; ++i) {
                toggleButtons[i].onclick = function() {
                    const parent = this.parentElement.parentElement.parentElement;
                    parent.classList.toggle('hide');
                    this.querySelector('i').className = parent.classList.contains('hide') ? 'el-icon-minus' : 'el-icon-check';
                    return false;
                };
            }
        </script>
    </body>
</html>
