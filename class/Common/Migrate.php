<?php

declare(strict_types=1);

namespace XoopsModules\Adslight\Common;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use InvalidArgumentException;
use RuntimeException;
use XoopsModules\Adslight\{
    Common\Configurator
};

/**
 * Class Migrate synchronize existing tables with target schema
 *
 * @category  Migrate
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2016 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */
class Migrate extends \Xmf\Database\Migrate
{
    private $moduleDirName;
    private $renameColumns;
    private $renameTables;

    /**
     * Migrate constructor.
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function __construct()
    {
        $class = __NAMESPACE__ . '\\' . 'Configurator';
        if (!\class_exists($class)) {
            throw new \RuntimeException("Class '$class' not found");
        }
        $configurator       = new $class();
        $this->renameTables = $configurator->renameTables;
        $this->renameColumns = $configurator->renameColumns;

        $this->moduleDirName = \basename(\dirname(__DIR__, 2));
        parent::__construct($this->moduleDirName);
    }


    /**
     * rename table if needed
     */
    private function renameTable(): void
    {
        foreach ($this->renameTables as $oldName => $newName) {
            if ($this->tableHandler->useTable($oldName) && !$this->tableHandler->useTable($newName)) {
                $this->tableHandler->renameTable($oldName, $newName);
            }
        }
    }


    /**
     * rename columns if needed
     */
    private function renameColumns(): void
    {
        $tables = new \Xmf\Database\Tables();
        foreach ($this->renameColumns as $table) {
            $tableName = $table['tablename'];
            $tableExists = $tables->useTable($tableName);
            if ($tableExists) {
                $oldName = $table['from'];
                $newName = $table['to'];
                $tableDetails=$tables->dumpTables();

                $attributes = $tables->getColumnAttributes($tableName, $oldName);
//                if (false !== \strpos($attributes, ' int(')) {
                if (false !== $attributes) {
                    $tables->alterColumn($tableName, $oldName, $attributes, $newName);

//                    $tables->getTableIndexes()  update($tableName, [$newName => "($newName)"], '', false);
//
//                    $tables->dropIndex($name, $table)
//                    $tables->addIndex($name, $table, $column, $unique = false)


                }
            }
        }
    }

    /**
     * Perform any upfront actions before synchronizing the schema
     *
     * Some typical uses include
     *   table and column renames
     *   data conversions
     */
    protected function preSyncActions()
    {
        // rename table
        if ($this->renameTables && \is_array($this->renameTables)) {
            $this->renameTable();
        }
        // rename column
        if ($this->renameColumns && \is_array($this->renameColumns)) {
            $this->renameColumns();
        }
    }
}
