<?php


namespace Lifo\CiscoISE;

class AncPolicy extends AbstractObject
{
    const string CONFIG_URI        = 'config/ancpolicy';
    const string CONFIG_NAME_URI   = self::CONFIG_URI . '/name';
    const string JSON_ROOT_KEY     = 'ErsAncPolicy';
    const string ACTION_QUARANTINE = 'QUARANTINE';
    const string ACTION_PORTBOUNCE = 'PORTBOUNCE';
    const string ACTION_SHUTDOWN   = 'SHUTDOWN';
    const array  VALID_ACTIONS     = [self::ACTION_QUARANTINE, self::ACTION_PORTBOUNCE, self::ACTION_SHUTDOWN];

    protected ?string $id          = null;
    protected ?string $name        = null;
    protected ?string $description = null;
    protected array   $actions     = [];

    public function __clone()
    {
        $this->id = null;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function setActions(array|string $actions): self
    {
        if (!is_iterable($actions)) $actions = [$actions];
        foreach ($actions as $act) {
            $this->addAction($act);
        }
        return $this;
    }

    public function addAction(string $action): self
    {
        if (!in_array($action, $this->actions) && in_array($action, self::VALID_ACTIONS)) {
            $this->actions[] = $action;
        }
        return $this;
    }

    public function removeAction(string $action): self
    {
        if (false !== $pos = array_search($action, $this->actions)) {
            array_splice($this->actions, $pos, 1);
        }
        return $this;
    }

}