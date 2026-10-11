<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class SauvegarderBase extends Command
{
    protected $signature = 'app:sauvegarder-base';

    protected $description = 'Sauvegarde la base de données SQLite';

    public function handle(): int
    {
        // Chemin de la base de données
        $source = database_path('database.sqlite');

        // Dossier de sauvegarde
        $dossier = storage_path('app/backups');

        // Vérifier que la base existe
        if (!File::exists($source)) {
            $this->error('Base de données introuvable : ' . $source);

            return self::FAILURE;
        }

        // Créer le dossier de sauvegarde si nécessaire
        if (!File::exists($dossier)) {
            File::makeDirectory($dossier, 0755, true);
        }

        // Nom du fichier avec date et heure
        $destination = $dossier . '/database_'
            . now()->format('Y-m-d_H-i-s') . '.sqlite';

        // Vérifier que SQLite est disponible
        $process = new Process([
            'sqlite3',
            $source,
            '.backup ' . $destination,
        ]);

        $process->run();

        // Afficher les erreurs SQLite
        if (!$process->isSuccessful()) {
            $this->error('Échec de la sauvegarde.');
            $this->error('Code de sortie : ' . $process->getExitCode());
            $this->error('Erreur : ' . $process->getErrorOutput());
            $this->error('Sortie : ' . $process->getOutput());

            return self::FAILURE;
        }

        // Vérifier le fichier créé
        if (
            !File::exists($destination)
            || filesize($destination) === 0
        ) {
            $this->error(
                'Le fichier de sauvegarde est absent ou vide : '
                . $destination
            );

            return self::FAILURE;
        }

        // Succès
        $this->info('Sauvegarde effectuée avec succès.');
        $this->info('Fichier : ' . $destination);
        $this->info(
            'Taille : ' . number_format(
                filesize($destination) / 1024 / 1024,
                2
            ) . ' Mo'
        );

        return self::SUCCESS;
    }
}
