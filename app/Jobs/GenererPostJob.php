<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Ai\Agents\PostGenerator;
use App\Models\RawContent;

class GenererPostJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public RawContent $rawContent) 
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = (new PostGenerator)->prompt($this->rawContent->contenu_brut);

        $this->rawContent->generatedPost()->create([
            'hook_propose'                  => $response['hook_propose'],
            'body_points'                   => $response['body_points'],
            'technical_readability_score'   => $response['technical_readability_score'],
            'suggested_hashtags'            => $response['suggested_hashtags'],
            'tone_compliance_justification' => $response['tone_compliance_justification'],
            'statut'                        => 'draft',
        ]);

        $this->rawContent->update(['statut' => 'traite']);
    }
}
