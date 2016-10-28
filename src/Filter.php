<?php


namespace ApiUtils;


use ApiUtils\Exceptions\FilterException;

class Filter
{

    /**
     * @var Builder
     */
    protected $query;

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var array
     */
    protected $mapper = [
        'eq' => '=',
        'neq' => '!=',
        'lk' => 'like',
        'ilk' => 'ilike',
        'gt' => '>',
        'lt' => '<',
        'in' => 'in',
        'nnul' => 'is not null'
    ];

    public function __construct(string $filter)
    {
        $this->parseFilter($filter);
        return $this;
    }

    public function apply(/*Builder*/ $query)
    {
        //todo instanceof
        $this->query = $query;
        if (count($this->filters) === 0) {
            return;
        }
        try {
            foreach ($this->filters as $filter) {
                $this->applyFilter($filter);
            }
        } catch (\Exception $e) {
            throw new FilterException();
        }
    }

    protected function parseFilter($filter)
    {
        if (empty($filter)) {
            return;
        }

        if (isset($filter[0]) && $filter[0] === "'") {
            $filter[0] = " ";
        }

        $last = strlen($filter) - 1;
        if (isset($filter[$last]) && $filter[$last] === "'") {
            $filter[$last] = " ";
        }

        $filters = explode('|', $filter);
        foreach ($filters as $filter) {
            $filter = trim($filter);
            array_push($this->filters, $filter);
        }
    }

    protected function applyFilter($filter)
    {
        $matches = $this->parseOne($filter);
        $this->applyOne($matches);
    }

    protected function parseOne($filter)
    {
        preg_match(
            '#^([a-z._]+) (' . implode('|', array_keys($this->mapper)) . ') (.+)$#',
            $filter,
            $matches,
            PREG_OFFSET_CAPTURE
        );

        return $matches;
    }

    protected function applyOne($matches)
    {

        if ($matches[2][0] == 'in') {
            $this->query->whereRaw($matches[0][0]);
            return;
        }

        $this->query->where($matches[1][0], $this->mapper[$matches[2][0]], $matches[3][0]);

    }

}
