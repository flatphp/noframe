<?php namespace Model;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    public function setUpdatedAt($value)
    {
        if (static::UPDATED_AT) {
            return parent::setUpdatedAt($value);
        }
        return $this;
    }

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}