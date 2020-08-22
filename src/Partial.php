<?php

namespace AcfGutenberg;

abstract class Partial extends Composer
{
    /**
     * Compose and register the defined field groups with ACF.
     *
     * @return void
     */
    public function compose()
    {
        if (empty($this->fields())) {
            return;
        }

        return $this->fields();
    }
}
