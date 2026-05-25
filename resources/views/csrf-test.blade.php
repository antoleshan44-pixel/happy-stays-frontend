<!DOCTYPE html>
<html>
<head>
    <title>CSRF Test - Eserian Homes</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .card { background: white; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        pre { background: #f0f0f0; padding: 10px; border-radius: 4px; overflow-x: auto; }
        .success { color: green; }
        .error { color: red; }
        button { background: #00288e; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #1e40af; }
    </style>
</head>
<body>
    <h1>🔧 CSRF Debug Tool</h1>

    <div class="card">
        <h2>📋 Current Session Info</h2>
        <pre id="sessionInfo">Loading...</pre>
    </div>

    <div class="card">
        <h2>📝 CSRF Token from Page</h2>
        <pre id="tokenDisplay"></pre>
    </div>

    <div class="card">
        <h2>🧪 Test POST Request (Same Page)</h2>
        <button onclick="testPost()">Send Test POST to /register</button>
        <div id="postResult" style="margin-top: 15px;"></div>
    </div>

    <div class="card">
        <h2>📋 Form HTML (First 500 chars)</h2>
        <pre id="formHtml"></pre>
    </div>

    <script>
        // Display CSRF token
        const token = document.querySelector('meta[name="csrf-token"]').content;
        document.getElementById('tokenDisplay').innerHTML = token;

        // Get session info
        fetch('/test-session')
            .then(res => res.json())
            .then(data => {
                document.getElementById('sessionInfo').innerHTML = JSON.stringify(data, null, 2);
            })
            .catch(err => {
                document.getElementById('sessionInfo').innerHTML = 'Error: ' + err.message;
            });

        // Get form HTML
        fetch('/register')
            .then(res => res.text())
            .then(html => {
                const formMatch = html.match(/<form[\s\S]*?<\/form>/i);
                if (formMatch) {
                    document.getElementById('formHtml').innerHTML = formMatch[0].substring(0, 500) + '...';
                } else {
                    document.getElementById('formHtml').innerHTML = 'No form found on /register page';
                }
            });

        function testPost() {
            const resultDiv = document.getElementById('postResult');
            resultDiv.innerHTML = 'Sending request...';
            resultDiv.style.color = 'blue';

            fetch('/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: 'Test User',
                    email: 'test_' + Date.now() + '@example.com',
                    phone: '0712345678',
                    password: '12345678',
                    password_confirmation: '12345678',
                    role: 'customer'
                })
            })
            .then(async response => {
                const text = await response.text();
                resultDiv.innerHTML = `
                    <strong>Status:</strong> ${response.status}<br>
                    <strong>Status Text:</strong> ${response.statusText}<br>
                    <strong>Response (first 500 chars):</strong><br>
                    <pre>${text.substring(0, 500)}</pre>
                `;

                if (response.status === 200 || response.status === 302) {
                    resultDiv.style.color = 'green';
                    resultDiv.innerHTML += '<br><span class="success">✅ SUCCESS! Registration is working!</span>';
                } else if (response.status === 419) {
                    resultDiv.style.color = 'red';
                    resultDiv.innerHTML += '<br><span class="error">❌ 419 ERROR: CSRF token mismatch. Session issue.</span>';
                } else {
                    resultDiv.style.color = 'orange';
                }
            })
            .catch(err => {
                resultDiv.innerHTML = 'Error: ' + err.message;
                resultDiv.style.color = 'red';
            });
        }
    </script>
</body>
</html>
