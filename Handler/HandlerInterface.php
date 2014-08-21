<?php
namespace Gos\Bundle\FormBundle\Handler;

interface HandlerInterface
{
    public function supportedAction();

    /**
     * @return AbstractHandler
     */
    public function perform($action);

    /**
     * @return AbstractHandler
     */
    public function with($data);

    /**
     * @return void
     */
    public function handle();
}
