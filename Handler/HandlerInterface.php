<?php
namespace Gos\Bundle\FormBundle\Handler;

interface HandlerInterface
{
    public function supportedAction();

    public function perform($action);

    public function with($data);

    public function handle();
}
