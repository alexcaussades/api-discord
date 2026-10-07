<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UserDiscordIdController;

class ApiEDController extends Controller
{
    
    public function actionWithInfo(string $id, string $info)
    {
        return response()->json(['id' => $id, 'info' => $info, 'status' => 'success']);
    }

    public function action(string $id, string $galaxie = "", string $id_discord = "")
    {
        // Approuver l'action pour l'utilisateur avec l'identifiant donné avce discordId via UserDiscordIdController
        $userDiscordIdController = new UserDiscordIdController();
        $action = $userDiscordIdController->approveUserDiscordIdAction($id_discord); 

        if ($action->getData()->status === 'approved') {
            return response()->json(['id' => $id, 'galaxie' => $galaxie ?: 'Null', 'status' => 'success', 'discordId' => $action->getData()->discordId]);
        } else {
            return response()->json(["Action not approved for user with id discord please contact the administrator."]);
        }

    }
}
