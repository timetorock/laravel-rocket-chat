<?php

namespace Timetorock\LaravelRocketChat\Models;

class Im extends Entity
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $fillable = [
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