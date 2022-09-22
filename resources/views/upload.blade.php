<html>
    <head>
        <title>
        </title>
    </head>
    <body>
        <form method="POST" action="{{ route('upload') }}" enctype="multipart/form-data">
            @csrf
            <input type="file" name="item" />
            <input type="submit" name="submit" value="submit" />
        </form>
    </body>
</html>
