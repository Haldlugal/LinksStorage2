<?php


class DataService
{
    private $data;

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $name
     * @param mixed $data
     */
    public function setData($name, $data)
    {
        $this->data[$name] = $data;
    }

}