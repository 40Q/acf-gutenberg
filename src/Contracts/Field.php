<?php

namespace AcfGutenberg\Contracts;

interface Field
{
    /**
     * The field group.
     *
     * @return \StoutLogic\AcfBuilder\FieldsBuilder|array
     */
    public function fields();
}
