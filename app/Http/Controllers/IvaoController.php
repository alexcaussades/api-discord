<?php

namespace App\Http\Controllers;

use Carbon\CarbonInterval;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class IvaoController extends Controller
{
    protected string $whazzup;

    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->whazzup = "https://api.ivao.aero/v2/tracker/whazzup";
    }

    public function index()
    {
        // connections/atc

        $request = Http::get($this->whazzup);

        $data = [
            "data" => [
                'atc' => $request->json('connections.atc') ?? ["error" => "No data available"],
                'pilot' => $request->json('connections.pilot') ?? ["error" => "No data available"],
                'total' => $request->json('connections.total') ?? ["error" => "No data available"]
            ]
        ];

        return $data;
    }

    public function metar(string $icao)
    {
        $icao = trim($icao);
        $request = Http::acceptJson()->timeout(10)->get("https://api.ivao.aero/v2/airports/{$icao}/metar", [
            "accept" => "application/json",
            "apiKey" => config('services.ivao.api_key')
        ]);
        $updatedAt = Carbon::parse($request->json('updatedAt') ?? now());
        $now = Carbon::now('Europe/Paris');
        $time = $now->diffForHumans($updatedAt);
        $data = [
            'data' => [
                'metar' => $request->json('metar') ?? ['error' => 'No data available'],
                'update' => $time ?? null
            ]
        ];
        return $data;
    }

    public function taf(string $icao)
    {
        $icao = trim($icao);
        $request = Http::acceptJson()->timeout(10)->get("https://api.ivao.aero/v2/airports/{$icao}/shortTaf", [
            "accept" => "application/json",
            "apiKey" => config('services.ivao.api_key')
        ]);
        $updatedAt = Carbon::parse($request->json('updatedAt') ?? now());
        $now = Carbon::now('Europe/Paris');
        $time = $now->diffForHumans($updatedAt);
        $data = [
            'data' => [
                'taf' => $request->json('shortTaf') ?? ['error' => 'No data available'],
                'update' => $time ?? null
            ]
        ];
        return $data;
    }

    public function atis(string $icao)
    {
        $icao = trim($icao);
        $request = Http::acceptJson()->timeout(10)->get("https://api.ivao.aero/v2/airports/{$icao}/atis/latest", [
            "accept" => "application/json",
            "apiKey" => config('services.ivao.api_key')
        ]);
        $updatedAt = Carbon::parse($request->json('updated_at') ?? now());
        $now = Carbon::now('Europe/Paris');
        $time = $now->diffForHumans($updatedAt);
        $data = [
            'data' =>[
                'revision' => $request->json('revision') ?? ['error' => 'No data available'],
                'information' => $request->json('lines')[1] ?? ['error' => 'No data available'],
                'update' => $time ?? null
            ], 
        ];
        return $data;
    }

     public function sso(Request $request)
    {
        // Now we can take care of the actual authentication
        $client_id = env("IVAO_CLIENT_ID");
        $client_secret = env("IVAO_CLIENT_SECRET");
        $redirect_uri = route("ivao.login-sso-callback");
        // Get all URLs we need from the server
        $openid_url = "https://api.ivao.aero/.well-known/openid-configuration";
        $openid_result = file_get_contents($openid_url, false);
        if ($openid_result === false) {
            /* Handle error */
            die("Error while getting openid data");
        }
        $openid_data = json_decode($openid_result, true);

        $base_url = $openid_data["authorization_endpoint"];
        $reponse_type = "code";
        $scopes = "profile configuration email";
        $state = rand(100000, 999999); // Random string to prevent CSRF attacks

        $query = [
            "response_type" => $reponse_type,
            "client_id" => $client_id,
            "scope" => $scopes,
            "redirect_uri" => $redirect_uri,
            "state" => $state,
        ];
        $full_url = "$base_url?" . http_build_query($query);

        if (isset($request->code) && isset($request->state)) {
            // User has been redirected back from the login page

            $code = $request->code; // Valid only 5 minutes

            $token_req_data = [
                "grant_type" => "authorization_code",
                "code" => $code,
                "client_id" => $client_id,
                "client_secret" => $client_secret,
                "redirect_uri" => $redirect_uri,
            ];

            // use key 'http' even if you send the request to https://...
            $token_options = [
                "http" => [
                    "header" =>
                        "Content-type: application/x-www-form-urlencoded\r\n",
                    "method" => "POST",
                    "content" => http_build_query($token_req_data),
                ],
            ];
            $token_context = stream_context_create($token_options);
            $token_result = file_get_contents(
                $openid_data["token_endpoint"],
                false,
                $token_context
            );
            if ($token_result === false) {
                /* Handle error */
                die("Error while getting token");
            }

            $token_res_data = json_decode($token_result, true);

            $access_token = $token_res_data["access_token"]; // Here is the access token
            $refresh_token = $token_res_data["refresh_token"]; // Here is the refresh token

            session([
                "ivao_tokens" => json_encode([
                    "access_token" => $access_token,
                    "refresh_token" => $refresh_token,
                ]),
            ]);
            return redirect()->route("ivao.login-sso");
            // header("Location: user.php"); // Remove the code and state from URL since they aren't valid anymore
        } elseif (session()->has("ivao_tokens")) {
            // User has already logged in

            $tokens = json_decode(session("ivao_tokens"), true);
            $access_token = $tokens["access_token"];
            $refresh_token = $tokens["refresh_token"];

            // Now we can use the access token to get the data

            $user_options = [
                "http" => [
                    "header" => "Authorization: Bearer $access_token\r\n",
                    "method" => "GET",
                    "ignore_errors" => true,
                ],
            ];
            $user_context = stream_context_create($user_options);
            $user_result = file_get_contents(
                $openid_data["userinfo_endpoint"],
                false,
                $user_context
            );
            $user_res_data = json_decode($user_result, true);

            if (
                isset($user_res_data["description"]) &&
                ($user_res_data["description"] ===
                    "This auth token has been revoked or expired" or
                $user_res_data["description"] ===
                    "Couldn't decode auth token")
            ) {
                // Access token expired, using refresh token to get a new one

                $token_req_data = [
                    "grant_type" => "refresh_token",
                    "refresh_token" => $refresh_token,
                    "client_id" => $client_id,
                    "client_secret" => $client_secret,
                ];

                $token_options = [
                    "http" => [
                        "header" =>
                            "Content-type: application/x-www-form-urlencoded\r\n",
                        "method" => "POST",
                        "content" => http_build_query($token_req_data),
                        "ignore_errors" => true,
                    ],
                ];
                $token_context = stream_context_create($token_options);
                $token_result = file_get_contents(
                    $openid_data["token_endpoint"],
                    false,
                    $token_context
                );
                if ($token_result === false) {
                    /* Handle error */
                    die("Error while refreshing token");
                }

                $token_res_data = json_decode($token_result, true);

                $access_token = $token_res_data["access_token"]; // Here is the new access token
                $refresh_token = $token_res_data["refresh_token"]; // Here is the new refresh token

                session([
                    "ivao_tokens" => json_encode([
                        "access_token" => $access_token,
                        "refresh_token" => $refresh_token,
                    ]),
                ]);

                return redirect()->route("ivao.login-sso");
            } else {
                // dd($user_res_data); // Display user data fetched with the access token
                return $this->handlerLogin($user_res_data);
            }
        } else {
            // First visit : Unauthenticated user
            return redirect($full_url);
        }
    }

    public function handlerLogin($user)
    {
        function staffLogin($data)
        {
            $staff = [];
            foreach ($data as $key => $value) {
                $staff[] = $value["id"];
            }
        
            $staff = implode(",", $staff);
            return $staff;
        }

        $finduser = User::where("vid", intval($user["id"]))->first();

        if ($finduser) {
            $finduser->firstname = $user["firstName"];
            $finduser->lastname = $user["lastName"];
            $finduser->email = $user["email"];
            $finduser->ratingatc = intval($user["rating"]["atcRating"]["id"]);
            $finduser->ratingpilot = intval(
                $user["rating"]["pilotRating"]["id"]
            );
            $finduser->division = $user["divisionId"];
            $finduser->country = $user["countryId"];
            $finduser->staff = staffLogin($user["userStaffPositions"]);

            $finduser->save();
            Auth::login($finduser);
        } else {
            $newUser = User::create([
                "vid" => intval($user["id"]),
                "firstName" => $user["firstName"],
                "lastName" => $user["lastName"],
                "email" => $user["email"],
                "ratingatc" => intval($user["rating"]["atcRating"]["id"]),
                "ratingpilot" => intval($user["rating"]["pilotRating"]["id"]),
                "division" => $user["divisionId"],
                "country" => $user["countryId"],
                "staff" => staffLogin($user["userStaffPositions"]),
                "password" => bcrypt("ivao"), // We are setting a default password for the user because Laravel requires it, but it won't be used since the only way to login is through IVAO SSO.
            ]);

            Auth::login($newUser);
        }

        $userlog = Auth::user();

        return redirect()->route("home");
    }
}
