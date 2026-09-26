<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DiscordServeurController extends Controller
{
    Private $discordServeur = [
        "url_du_serveur_discord" => "https://discord.com/api/guilds/515800610852044800/widget.json",
        "url_du_serveur_discord_invite" => "https://discord.com/invite/CDntF5H",
        "url_du_serveur_discord_widget" => "https://discord.com/api/guilds/515800610852044800/widget.json",
        "url_du_serveur_discord_de_test" => "https://discord.com/api/guilds/794195224134025256/widget.json"
    ];
    
    public function namePages()
    {
        return [
            "discord_serveur" => "Discord Serveur",
            "discord_serveur_invite" => "Discord Serveur Invite",
            "discord_serveur_widget" => "Discord Serveur Widget",
            "discord_serveur_de_test" => "Discord Serveur De Test",
            "presence" => "Presence"
        ];

    }

    public function getDiscordServeur()
    {
        return $this->discordServeur;
    }

    public function getDiscordServeurInvite()
    {
       return $this->discordServeur["url_du_serveur_discord_invite"];
    }
    
    public function getDiscordServeurWidget()
    {
        // changer le title pour renomer la page sur version html
        return view('discord_serveur_widget', [
            'title' => 'Discord Serveur Widget',
            'url' => $this->discordServeur["url_du_serveur_discord_widget"]
        ]);
    }

    public function getDiscordServeurDeTest()
    {
        return $this->discordServeur["url_du_serveur_discord_de_test"];
    }

    public function getpresence()
    {
        $discordResponse = Http::get($this->discordServeur["url_du_serveur_discord"]);
        return json_decode($discordResponse->body(), true)["presence_count"];
    }
}
