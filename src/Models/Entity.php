<?php


namespace Timetorock\LaravelRocketChat\Models;


class Entity
{
    /**
     * Parameters of the object that
     * can be filled fast from array
     * @var array
     */
    protected array $fillable = [];

    /**
     * Entity constructor. You can create entity with pre filled data.
     * By default empty entity is created.
     *
     * @param array $userData
     */
    public function __construct(array $userData = [])
    {
        $this->fill($userData);
    }

    /**
     * Fast way to populate user object with parameters.
     *
     * @param array $params
     *
     * @return $this
     */
    public function fill(array $params): Entity
    {
        foreach ($params as $field => $value) {

            if (!in_array($field, $this->fillable, true)) {
                continue;
            }

            $this->{$field} = $value;
        }

        return $this;
    }

    /**
     * Allows us to get data for API call from current object.
     *
     * @return array
     */
    public function getFillableData(): array
    {
        $data = [];

        foreach ($this->fillable as $field) {
            if (empty($this->{$field})) {
                continue;
            }
            $data[ $field ] = $this->{$field};
        }

        return $data;
    }

    /**
     * With this function you can limit or
     * extend of fillable params.
     *
     * @param array $fillable
     *
     * @return $this
     */
    public function setFillable(array $fillable): Entity
    {
        $this->fillable = $fillable;

        return $this;
    }
}