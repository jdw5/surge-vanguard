<?php

trait IsExportable
{
    protected bool $export = true;

    /**
     * Set the export to true, chainable.
     *
     * @param  bool  $export
     */
    public function export(): static
    {
        $this->setExport(true);

        return $this;
    }

    /**
     * Set the export to false, chainable.
     */
    public function dontExport(): static
    {
        $this->setExport(false);

        return $this;
    }

    /**
     * Set the export quietly.
     */
    protected function setExport(bool $export): void
    {
        $this->export = $export;
    }
}
