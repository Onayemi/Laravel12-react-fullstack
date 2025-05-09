<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <h1>Title: {{$title}}</h1>
    <h2>Date: {{$date}}</h2>
    <table class="table table-bordered border border-primary">
        <thead>
            <tr>
                {{-- <td>User Name</td> --}}
                <td>Title</td>
                <td>Category</td>
                <td>Content</td>
                <td>Status</td>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $item)
            <tr>
                {{-- <td>{{$item->user_id}}</td> --}}
                <td>{{$item->title}}</td>
                <td>{{$item->category}}</td>
                <td>{{$item->content}}</td>
                <td>{{$item->status}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>