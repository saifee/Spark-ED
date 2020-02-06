<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <form method="post" enctype="multipart/form-data">
        {!! csrf_field(); !!}
        <input type="file" name="csv_file">
        <label><input type="checkbox" name="truncate" value="1"> clear previous data</label>
        <button>upload</button>
    </form>
</body>
</html>
