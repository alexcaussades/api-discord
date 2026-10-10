<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Farmland;
use App\Models\FarmlandReservation;
use App\Models\Player;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FarmlandController extends Controller
{
    /**
     * Liste des terrains.
     */
    public function index(): JsonResponse
    {
        $farmlands = Farmland::with([
            'map',
            'activeReservation.player'
        ])->get();

        return response()->json([
            'success' => true,
            'data' => $farmlands,
        ]);
    }

    /**
     * Informations d'un terrain.
     */
    public function show(Farmland $farmland): JsonResponse
    {
        $farmland->load([
            'map',
            'activeReservation.player'
        ]);

        return response()->json([
            'success' => true,
            'data' => $farmland,
        ]);
    }

    /**
     * Réserver un terrain. je recois lutilisateur via son discord_id.
     * recupere le discord_id du joueur verifie si le terrain est libre.
     * Si le terrain est libre, crée une réservation active pour le joueur.
     */
    public function reserve(
        Request $request,
        Farmland $farmland
    ): JsonResponse {
        $validated = $request->validate([
            'discord_id' => [
                'required',
                'integer',
                'exists:players,discord_id',
            ],
        ]);

        // Récupère le joueur correspondant au discord_id fourni.
        $player = Player::where('discord_id', $validated['discord_id'])
            ->first();

        // Vérifie si le joueur existe.
        // Si le joueur n'existe pas, retourne une réponse d'erreur.

        if (!$player) {
            return response()->json([
                'success' => false,
                'message' => 'Cet utilisateur n’existe pas dans la table players.',
            ], 404);
        }

        return DB::transaction(function () use ($farmland, $player) {
            $farmland = Farmland::whereKey($farmland->id)
                ->lockForUpdate()
                ->first();

            if ($farmland->status !== 'free') {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce terrain est déjà réservé.',
                ], 409);
            }

            $reservation = FarmlandReservation::create([
                'farmland_id' => $farmland->id,
                'player_id' => $player->id,
                'reserved_at' => now(),
                'status' => 'active',
            ]);

            $farmland->update([
                'status' => 'reserved',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Terrain réservé avec succès.',
                'data' => [
                    'farmland' => $farmland->fresh(),
                    'player' => $player,
                    'reservation' => $reservation,
                ],
            ], 201);
        });
    }

    /**
     * Libérer un terrain.
     */
    public function release(
        Farmland $farmland
    ): JsonResponse {
        return DB::transaction(function () use ($farmland) {

            $reservation = $farmland
                ->activeReservation()
                ->first();

            if (!$reservation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce terrain n’est pas réservé.',
                ], 404);
            }

            $reservation->update([
                'status' => 'released',
                'released_at' => now(),
            ]);

            $farmland->update([
                'status' => 'free',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Terrain libéré.',
                'data' => $farmland->fresh(),
            ], 200);
        });
    }

    public function check(
        Farmland $farmland
    ): JsonResponse {
        $reservation = $farmland
            ->activeReservation()
            ->first();

        if (!$reservation) {
            return response()->json([
                'success' => false,
                'message' => 'Ce terrain n’est pas réservé.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ce terrain est réservé.',
            'data' => $reservation,
        ]);
    }
}