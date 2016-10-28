<?php


namespace ApiUtils;


use ApiUtils\Exceptions\SorterException;

class Sorter
{

    /**
     * @var Builder
     */
    protected $query;

    /**
     * @var array
     */
    protected $sorts = [];

    public function __construct(string $sorts)
    {
        $this->parseSorts($sorts);
        return $this;
    }

    public function apply(/*Builder*/ $query)
    {
        //todo instanceof
        $this->query = $query;
        if (count($this->sorts) === 0) {
            return;
        }
        try {
            foreach ($this->sorts as $sort) {
                $direction = 'ASC';
                if ($sort[0] == '!') {
                    $direction = 'DESC';
                    $sort = substr($sort, 1);
                }
                $this->query->orderBy($sort, $direction);
            }
        } catch (\Exception $e) {
            throw new SorterException();
        }
    }

    protected function parseSorts($sorts)
    {
        if (empty($sorts)) {
            return;
        }

        if (isset($sorts[0]) && $sorts[0] === "'") {
            $sorts[0] = " ";
        }

        $last = strlen($sorts) - 1;
        if (isset($sorts[$last]) && $sorts[$last] === "'") {
            $sorts[$last] = " ";
        }

        $sorts = explode('|', $sorts);
        foreach ($sorts as $sort) {
            $sort = trim($sort);
            array_push($this->sorts, $sort);
        }
    }

}
