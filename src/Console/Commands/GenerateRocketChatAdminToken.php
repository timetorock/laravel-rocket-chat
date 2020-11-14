<?php


namespace Timetorock\LaravelRocketChat\Console\Commands;

use Httpful\Exception\ConnectionErrorException;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Timetorock\LaravelRocketChat\Client\Client;
use Timetorock\LaravelRocketChat\Exceptions\InvalidCredentialsException;

class GenerateRocketChatAdminToken extends Command
{
    use ConfirmableTrait;

    const RC_ADMIN_ID = 'RC_ADMIN_ID';
    const RC_ADMIN_TOKEN = 'RC_ADMIN_TOKEN';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rc:admin:generate
                    {--show : Display the key instead of modifying files}
                    {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate rocket chat admin user/token pair';

    /**
     * Execute the console command.
     *
     * @return void
     * @throws ConnectionErrorException
     * @throws InvalidCredentialsException
     */
    public function handle()
    {
        $username = $this->ask('Admin username');
        $password = $this->ask('Admin password');

        if (!$username || !$password) {
            $this->error("invalid credentials provided");
            return;
        }

        $rocketChatClient = new Client();
        $auth = $rocketChatClient->authToken($username, $password);

        if ($this->option('show')) {
            $this->line('<comment>'.$auth->getId().'</comment>');
            $this->line('<comment>'.$auth->getToken().'</comment>');
            return;
        }

        // Next, we will replace the application key in the environment file so it is
        // automatically setup for this developer. This key gets generated using a
        // secure random byte generator and is later base64 encoded for storage.
        if (! $this->setAdminTokenCredentials($auth->getId(), $auth->getToken())) {
            return;
        }

        $this->laravel['config']['laravel-rocket-chat.admin_id'] = $auth->getId();
        $this->laravel['config']['laravel-rocket-chat.admin_token'] = $auth->getToken();

        $this->info('Rocket chat admin token credentials set successfully.');
    }

    /**
     * Set the application key in the environment file.
     *
     * @param $id
     * @param $token
     *
     * @return bool
     */
    protected function setAdminTokenCredentials($id, $token)
    {
        $currentAdminID = $this->laravel['config']['laravel-rocket-chat.admin_id'];
        $currentAdminToken = $this->laravel['config']['laravel-rocket-chat.admin_token'];

        if (strlen($currentAdminID) !== 0 && (! $this->confirmToProceed())) {
            return false;
        }

        if (strlen($currentAdminToken) !== 0 && (! $this->confirmToProceed())) {
            return false;
        }

        $this->writeNewEnvironmentFileWith(self::RC_ADMIN_ID, $id);
        $this->writeNewEnvironmentFileWith(self::RC_ADMIN_TOKEN, $token);

        return true;
    }

    /**
     * Write a new environment file with the given key.
     *
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    protected function writeNewEnvironmentFileWith(string $key, string $value)
    {
        file_put_contents($this->laravel->environmentFilePath(), preg_replace(
            $this->keyReplacementPattern($key),
            sprintf('%s=%s', $key, $value),
            file_get_contents($this->laravel->environmentFilePath())
        ));
    }

    /**
     * Get a regex pattern that will match env $key with any random value.
     *
     * @param string $key
     *
     * @return string
     */
    protected function keyReplacementPattern(string $key)
    {
        return "/^$key=.*/m";
    }
}
