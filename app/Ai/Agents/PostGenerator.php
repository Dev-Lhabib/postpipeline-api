<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Enums\Lab;
use Stringable;

#[Provider(Lab::Groq)]
#[Model('meta-llama/llama-4-scout-17b-16e-instruct')]
class PostGenerator implements Agent, HasStructuredOutput
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'Tu es un ghostwriter pour la tech community sur X. À partir d\'un contenu technique brut (notes de dev, README, article), tu produis un post optimisé : un hook accrocheur, des points clés courts, un score de lisibilité technique, des hashtags pertinents, et une justification du respect du ton.';
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'hook_propose' => $schema->string()->required(),
            'body_points' => $schema->array()->items($schema->string())->required(),
            'technical_readability_score' => $schema->integer()->required(),
            'suggested_hashtags' => $schema->array()->items($schema->string())->required(),
            'tone_compliance_justification' => $schema->string()->required(),
        ];
    }
}
