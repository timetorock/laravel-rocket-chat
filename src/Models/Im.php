<?php

namespace Timetorock\LaravelRocketChat\Models;

class Im extends Entity
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var array
     */
    private $fillable = [
        "id"
    ];

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


}