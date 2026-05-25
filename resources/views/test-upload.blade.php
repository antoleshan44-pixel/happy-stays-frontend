<!DOCTYPE html>
<html>
<head>
    <title>Test Video Upload</title>
</head>
<body>
    <h1>Test Video Upload</h1>
    
    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div style="color: red;">{{ session('error') }}</div>
    @endif
    
    @if($errors->any())
        <div style="color: red;">
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif
    
    <form action="{{ route('owner.video.upload', $property->id) }}" 
          method="POST" 
          enctype="multipart/form-data">
        @csrf
        <div>
            <label>Video File:</label>
            <input type="file" name="video" accept="video/mp4,video/quicktime,video/x-msvideo" required>
        </div>
        <div>
            <label>Title:</label>
            <input type="text" name="title">
        </div>
        <div>
            <label>Description:</label>
            <textarea name="description"></textarea>
        </div>
        <button type="submit">Upload Video</button>
    </form>
    
    <hr>
    
    <h2>Existing Videos for this Property:</h2>
    @if($property->videos->count() > 0)
        @foreach($property->videos as $video)
            <div>
                <p><strong>{{ $video->title ?: 'Untitled' }}</strong></p>
                <video width="320" height="240" controls>
                    <source src="{{ asset('storage/' . $video->video_path) }}" type="video/mp4">
                </video>
                <form action="{{ route('owner.video.delete', [$property->id, $video->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="color: red;">Delete</button>
                </form>
            </div>
            <hr>
        @endforeach
    @else
        <p>No videos uploaded yet.</p>
    @endif
</body>
</html>