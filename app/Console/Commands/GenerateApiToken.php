<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

use function Laravel\Prompts\search;

class GenerateApiToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-api-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate an api token for a user';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $user_id = search(
            label  : 'Search for the user to generate the token for',
            options: fn ( string $search ) => strlen( $search ) > 0
                ? User::whereLike( 'name', "%{$search}%" )->pluck( 'name', 'id' )->all()
                : []
        );

        $token = User::find( $user_id )
            ->createToken( 'api-token' )
            ->plainTextToken;

        $this->info( 'Token: ' . $token );

        return Command::SUCCESS;
    }
}
