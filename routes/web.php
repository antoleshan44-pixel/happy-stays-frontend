// ============================================
// API ROUTE TO SET TOKEN IN SESSION (Called from frontend after login)
// ============================================

Route::post('/api/set-token', function(Request $request) {
    $token = $request->input('token');
    $user = $request->input('user');
    
    if ($token) {
        session(['api_token' => $token]);
        session(['user' => $user]);
        
        // Also store in a more persistent way if needed
        if ($request->input('remember', false)) {
            session()->put('remember_token', $token);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Token stored in session'
        ]);
    }
    
    return response()->json([
        'success' => false, 
        'message' => 'No token provided'
    ], 400);
})->name('set.token');
