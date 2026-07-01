<?php

namespace App\Http\Controllers\Api;

use App\Ai\Agents\PostGenerator;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContentRequest;

class ContentController extends Controller
{
    public function repurpose(StoreContentRequest $request)
    {
        $rawContent = auth()->user()->rawContents()->create([
            'blueprint_id' => $request->validated('blueprint_id'),
            'contenu_brut' => $request->validated('contenu_brut'), 
            'statut' => 'en_attente',
        ]);

        $response = (new PostGenerator)->prompt($rawContent->contenu_brut);

        $rawContent->generatedPost()->create([
            'hook_propose'                  => $response['hook_propose'],
            'body_points'                   => $response['body_points'],
            'technical_readability_score'   => $response['technical_readability_score'],
            'suggested_hashtags'            => $response['suggested_hashtags'],
            'tone_compliance_justification' => $response['tone_compliance_justification'],
            'statut'                        => 'draft',
        ]);

        $rawContent->update(['statut' => 'traite']);

        return response()->json([
            'message'        => 'Post généré.',
            'raw_content_id' => $rawContent->id,
        ]);
    }
}