<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API ASERNUM HOTEL - Getting Started</title>

    <!-- GitHub Markdown CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/github-markdown-css/5.2.0/github-markdown-light.min.css">

    <!-- Prism.js pour la coloration syntaxique (optionnel) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>

    <!-- Tailwind pour mise en page -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background-color: #f8fafc;
        }

        .markdown-body {
            padding: 2rem;
        }

        /* Amélioration des tableaux */
        .markdown-body table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        .markdown-body th,
        .markdown-body td {
            border: 1px solid #d0d7de;
            padding: 0.5rem 1rem;
            text-align: left;
        }

        .markdown-body th {
            background-color: #f6f8fa;
            font-weight: 600;
        }

        .markdown-body tr:nth-child(even) td {
            background-color: #f6f8fa;
        }
    </style>
</head>
<body class="min-h-screen flex justify-center items-start py-8">

    <article class="markdown-body max-w-4xl bg-white rounded-lg shadow-lg">
        {!! $content !!}
    </article>

</body>
</html>
