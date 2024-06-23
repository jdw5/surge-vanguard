<?php

trait IsExportable
{
    protected bool $export = true;

    /**
     * Set the export to true, chainable.
     * 
     * @param bool $export
     * @return static
     */
    public function export(): static
    {
        $this->setExport(true);
        return $this;
    }

    /**
     * Set the export to false, chainable.
     * 
     * @return static
     */
    public function dontExport(): static
    {
        $this->setExport(false);
        return $this;
    }
    
    /**
     * Set the export quietly.
     * 
     * @return void
     */
    protected function setExport(bool $export): void
    {
        $this->export = $export;
    }
}