<!DOCTYPE html>
<html>
<head>
    <title>Test Video Upload</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial; padding: 20px; max-width: 600px; margin: 0 auto; }
        .success { background: #10b981; color: white; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .error { background: #ef4444; color: white; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .info { background: #3b82f6; color: white; padding: 10px; border-radius: 5px; margin: 10px 0; }
        input, button, textarea { margin: 10px 0; padding: 10px; border-radius: 5px; border: 1px solid #ccc; width: 100%; }
        button { background: #00288e; color: white; cursor: pointer; border: none; }
        button:hover { background: #1e40af; }
    </style>
</head>
<body>
    <h1>Test Video Upload</h1>
    
    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif
    
    <!-- Use route() helper instead of hardcoded URL -->
    <form action="{{ route('owner.video.upload', 2) }}" 
          method="POST" 
          enctype="multipart/form-data">
        @csrf
        <div>
            <label>Property ID: 2</label>
        </div>
        <div>
            <label>Video File (MP4 only, max 50MB):</label>
            <input type="file" name="video" accept="video/mp4" required>
        </div>
        <div>
            <label>Title:</label>
            <input type="text" name="title" placeholder="Video Title">
        </div>
        <div>
            <label>Description:</label>
            <textarea name="description" rows="3" placeholder="Video Description"></textarea>
        </div>
        <button type="submit">Upload Video</button>
    </form>
    
    <hr>
    
    <h2>Debug Information</h2>
    <div class="info">
        <strong>Route URL:</strong> {{ route('owner.video.upload', 2) }}<br>
        <strong>Route Name:</strong> owner.video.upload<br>
        <strong>CSRF Token:</strong> {{ csrf_token() }}<br>
    </div>
</body>
</html>