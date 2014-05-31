<?php
namespace Gos\Bundle\FormBundle\Handler;

abstract class AbstractHandler implements HandlerInterface
{
    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var string
     */
    protected $scheduleAction;

    /**
     * @param string $action
     *
     * @return $this
     */
    public function perform($action)
    {
        if(in_array($action, array_keys($actions = $this->supportedAction()))){
            $this->scheduleAction = $actions[$action];
        }

        return $this;
    }

    /**
     * @param mixed $data
     *
     * @return $this
     */
    public function with($data)
    {
        $this->data = $data;

        return $this;
    }

    protected function doPerform()
    {
        $this->{$this->scheduleAction}($this->data);
    }

    public function handle()
    {
        $this->doPerform();
    }
} 