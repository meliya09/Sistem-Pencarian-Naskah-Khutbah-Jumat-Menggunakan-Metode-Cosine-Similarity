<!DOCTYPE html>
<html lang="en">
    @include('templates.header')
    <body>

        @include('templates.navbar')
        <div class="container-fluid">
            @include('templates.error')
            @yield('content')
        </div>

    </body>
</html>
