Nice 🚀 let’s break down Option C (auto-login into Laravel before hitting /oauth/authorize).

Right now, your React login form posts to Laravel → Laravel validates credentials → but Passport needs the user to be logged in via the web guard before /oauth/authorize can work.

So what we'll do is:

React posts login credentials to a Laravel API endpoint.

Laravel authenticates the user and starts a session (Auth::login()).

Laravel redirects to /oauth/authorize?... with the same params React gave you.

Now the user is authenticated when hitting /oauth/authorize, so Passport skips the "Route [login] not defined" error.

🔹 Implementation

1. Create a controller
   // app/Http/Controllers/Auth/OAuthLoginController.php
   namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OAuthLoginController extends Controller
{
public function login(Request $request)
{
$credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // user authenticated → redirect to oauth/authorize
            $query = http_build_query([
                'client_id'     => $request->input('client_id'),
                'redirect_uri'  => $request->input('redirect_uri'),
                'response_type' => 'code',
                'scope'         => $request->input('scope', ''),
                'code_challenge' => $request->input('code_challenge'),
                'code_challenge_method' => 'S256',
            ]);

            return redirect('/oauth/authorize?' . $query);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

}

2. Add route
   // routes/web.php
   use App\Http\Controllers\Auth\OAuthLoginController;

Route::post('/oauth/login', [OAuthLoginController::class, 'login'])->name('oauth.login');

3. Update React form submit

Instead of redirecting straight to /oauth/authorize, send credentials to /oauth/login:

const handleSubmit = async (data: SigninSchema) => {
const verifier = generateVerifier();
const challenge = await generateChallenge(verifier);

localStorage.setItem("pkce_verifier", verifier);

const params = {
email: data.email,
password: data.password,
client_id: "YOUR_CLIENT_ID",
redirect_uri: "http://localhost:5173/auth/callback",
response_type: "code",
scope: "\*",
code_challenge: challenge,
};

// This POST will set the Laravel session + redirect
const form = document.createElement("form");
form.method = "POST";
form.action = "http://localhost:8000/oauth/login";

Object.entries(params).forEach(([k, v]) => {
const input = document.createElement("input");
input.type = "hidden";
input.name = k;
input.value = v as string;
form.appendChild(input);
});

document.body.appendChild(form);
form.submit();
};

4. Flow after login

User submits login → React posts to /oauth/login.

Laravel validates → Auth::login() starts session.

Laravel redirects internally to /oauth/authorize?....

Passport sees user is logged in → shows approval screen (or auto-approves if you set that up).

Redirects back to React with ?code=....

React exchanges code for token using /oauth/token + stored code_verifier.

⚠️ Notes

This requires Laravel session cookies to be enabled (so user stays logged in for /oauth/authorize).

If you’re running Laravel API-only, you must keep web guard enabled for this flow.

Use HTTPS in real environments so session cookies are secure.

👉 Do you want me to extend this so approval is also auto-skipped for first-party clients (so after login, users go straight back to React without the approval screen)?

composer require nyholm/psr7
