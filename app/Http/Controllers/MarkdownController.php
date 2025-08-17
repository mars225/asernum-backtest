<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\CommonMark\CommonMarkConverter;
use Illuminate\Support\Facades\File;

class MarkdownController extends Controller
{
    public function showMarkdown()
    {
        // Chemin vers le fichier à la racine du projet
        $path = base_path('README.md');

        // Vérifier que le fichier existe
        if (!File::exists($path)) {
            abort(404, 'Fichier Markdown introuvable');
        }

        // Lire le contenu
        $markdown = File::get($path);

        // Convertir en HTML
        $converter = new CommonMarkConverter();
        $html = $converter->convert($markdown);

        // Retourner à la vue Blade
        return view('markdown', ['content' => $html]);
    }
}
