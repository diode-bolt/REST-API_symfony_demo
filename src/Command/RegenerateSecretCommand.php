<?php

namespace App\Command;

use sixlive\DotenvEditor\DotenvEditor;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:regenerate-secret',
    description: 'regenerate secret key',
)]
class RegenerateSecretCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $secret = bin2hex(random_bytes(16));
        $filepath = realpath(dirname(__file__).'/../..') . '/.env';
        $io->note(sprintf('Editing file: %s', $filepath));

        $editor = new DotenvEditor();
        $editor->load($filepath);
        $editor->set('APP_SECRET', $secret);

        if ($editor->has('JWT_PASSPHRASE')) {
            $secretJWT = bin2hex(random_bytes(32));
            $editor->set('JWT_PASSPHRASE', $secretJWT);
        }

        if($editor->save()) {
            $io->success('New APP SECRETS was generated!');
            return Command::SUCCESS;
        }


        $io->error('can`t save .env file');
        return Command::FAILURE;
    }
}
