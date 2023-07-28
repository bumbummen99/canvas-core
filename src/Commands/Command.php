<?php

namespace Orchestra\Canvas\Core\Commands;

use Illuminate\Console\Concerns\CallsCommands;
use Illuminate\Console\Concerns\HasParameters;
use Illuminate\Console\Concerns\InteractsWithIO;
use Illuminate\Console\OutputStyle;
use Illuminate\Console\View\Components\Factory;
use Orchestra\Canvas\Core\Presets\Preset;
use Symfony\Component\Console\Command\Command as SymfonyConsole;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends \Symfony\Component\Console\Command\Command
{
    use CallsCommands,
        HasParameters,
        InteractsWithIO;

    /**
     * Canvas preset.
     *
     * @var \Orchestra\Canvas\Core\Presets\Preset
     */
    protected $preset;

    /**
     * Construct a new generator command.
     */
    public function __construct(Preset $preset)
    {
        $this->preset = $preset;

        parent::__construct();

        $this->specifyParameters();
    }

    /**
     * Initializes the command after the input has been bound and before the input
     * is validated.
     *
     * @return void
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = new OutputStyle($input, $output);

        $this->components = new Factory($this->output);
    }

    /**
     * Run the console command.
     *
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output): int
    {
        return parent::run($this->input, $this->output);
    }

    /**
     * Resolve the console command instance for the given command.
     *
     * @param  \Symfony\Component\Console\Command\Command|string  $command
     * @return \Symfony\Component\Console\Command\Command
     */
    protected function resolveCommand($command)
    {
        return $this->getApplication()->find(
            $command instanceof SymfonyConsole
                ? $command->getName()
                : $command
        );
    }
}
