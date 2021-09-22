<?php


namespace Lifo\CiscoISE;

class AncPolicy extends AbstractObject
{
    const CONFIG_URI        = 'config/ancpolicy';
    const CONFIG_NAME_URI   = self::CONFIG_URI . '/name';
    const JSON_ROOT_KEY     = 'ErsAncPolicy';
    const ACTION_QUARANTINE = 'QUARANTINE';
    const ACTION_PORTBOUNCE = 'PORTBOUNCE';
    const ACTION_SHUTDOWN   = 'SHUTDOWN';
    const VALID_ACTIONS     = [self::ACTION_QUARANTINE, self::ACTION_PORTBOUNCE, self::ACTION_SHUTDOWN];

    protected ?string $id          = null;
    protected ?string $name        = null;
    protected ?string $description = null;
    protected array   $actions     = [];

    public function __clone()
    {
        $this->id = null;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     *
     * @return self
     */
    public function setId(?string $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return self
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * get actions
     *
     * @return string[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * Set actions
     *
     * @param string|string[] $actions
     *
     * @return AncPolicy
     */
    public function setActions($actions): self
    {
        if (!is_iterable($actions)) $actions = [$actions];
        foreach ($actions as $act) {
            $this->addAction($act);
        }
        return $this;
    }

    /**
     * Add an action
     *
     * @param string $action
     *
     * @return $this
     */
    public function addAction(string $action): self
    {
        if (!in_array($action, $this->actions) && in_array($action, self::VALID_ACTIONS)) {
            $this->actions[] = $action;
        }
        return $this;
    }

    /**
     * Remove an action
     *
     * @param string $action
     *
     * @return $this
     */
    public function removeAction(string $action): self
    {
        if (false !== $pos = array_search($action, $this->actions)) {
            array_splice($this->actions, $pos, 1);
        }
        return $this;
    }

}