<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserDiscordIdController extends Controller
{
    // j'ai besoin de cette classe pour gérer les identifiants Discord des utilisateurs dans l'application

    // Méthodes qui vérifient les identifiants Discord des utilisateurs via leur userId dans une liste bien définie

    // Exemple de liste bien définie des identifiants Discord des utilisateurs
    private $userDiscordIds = [
        "219534038212411392" => [
            'username' => 'max',
            'discordId' => '219534038212411392'
        ],
        "1013557370162532482" => [
            'username' => 'htsr',
            'discordId' => '1013557370162532482'
        ],
        "1145762103655936122" => [
            'username' => 'wiwi',
            'discordId' => '1145762103655936122'
        ],
        "724707874915221659" => [
            'username' => 'antho',
            'discordId' => '724707874915221659'
        ],
        "439378290344460301" => [
            'username' => 'alex',
            'discordId' => '439378290344460301'
        ],
        // Ajoutez d'autres utilisateurs ici
    ];

    public function getUserDiscordId(string $userId)
    {
        // Logique pour récupérer l'identifiant Discord de l'utilisateur
        $discordId = $this->userDiscordIds[$userId] ?? 'exemple_discord_id';
        return response()->json(['userId' => $userId, 'discordId' => $discordId]);
    }

   public function approveUserDiscordIdAction(string $discordId)
   {
       // Logique pour approuver l'identifiant Discord de l'utilisateur pour effectuer une action spécifique dans les autres parties de l'application
       if (in_array($discordId, array_column($this->userDiscordIds, 'discordId'))) {
           // Marquer l'utilisateur comme approuvé pour l'action spécifique
           $user = array_filter($this->userDiscordIds, function($user) use ($discordId) {
               return $user['discordId'] === $discordId;
           });
           $user = array_values($user)[0] ?? null;
           return response()->json(['discordId' => $discordId, 'status' => 'approved']);
       } else {
           return response()->json(['discordId' => $discordId, 'status' => 'not_found']);
       }
       
   }
}
