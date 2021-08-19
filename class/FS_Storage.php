<?php

declare(strict_types=1);

namespace XoopsModules\Adslight;

// ------------------------------------------------------------------------- //
//                       XOOPS - Module MP Manager                           //
//                       <https://www.xoops.org>                             //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //
//                 Votre nouveau systeme de messagerie priver                //
//                                                                           //
//                               "MP"                                        //
//                                                                           //
//                       http://lexode.info/mods                             //
//                                                                           //
//                                                                           //
//---------------------------------------------------------------------------//

/**
 * Class FS_Storage
 * @package XoopsModules\Adslight
 */
class FS_Storage
{
    /**
     * @var string
     */
    public $rootDir;

    /**
     * FS_Storage constructor.
     * @param $rootDir
     */
    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    /**
     * @param $location
     */
    public static function deldir($location): void
    {
        if (\is_dir($location)) {
            $all = \opendir($location);
            while ($file = \readdir($all)) {
                if ('..' !== $file && '.' !== $file && \is_dir("${location}/${file}")) {
                    self::deldir("${location}/${file}");
                    if (\file_exists("${location}/${file}")) {
                        \rmdir("${location}/${file}");
                    }
                    unset($file);
                } elseif (!\is_dir("${location}/${file}")) {
                    if (\file_exists("${location}/${file}")) {
                        \unlink("${location}/${file}");
                    }
                    unset($file);
                }
            }
            \closedir($all);
            \rmdir($location);
        } elseif (\is_file((string)$location)) {
            \unlink((string)$location);
        }
    }

    /**
     * @param $fichier
     * @return false|string
     */
    public static function date_modif($fichier)
    {
        $tmp = \filemtime($fichier);
        return \date('d/m/Y H:i', $tmp);
    }

    // A function to copy files from one directory to another one, including subdirectories and
    // nonexisting or newer files. Function returns number of files copied.
    // This function is PHP implementation of Windows xcopy  A:\dir1\* B:\dir2 /D /E /F /H /R /Y
    // Syntaxis: [$number =] dircopy($sourcedirectory, $destinationdirectory [, $verbose]);
    // Example: $num = dircopy('A:\dir1', 'B:\dir2', 1);

    /**
     * @param      $srcdir
     * @param      $dstdir
     * @param      $errors
     * @param      $success
     * @param bool $verbose
     */
    public static function dircopy($srcdir, $dstdir, &$errors, &$success, $verbose = false): int
    {
        $num = 0;
        if (!\is_dir($dstdir) && (!\mkdir($dstdir) && !\is_dir($dstdir))) {
            throw new \RuntimeException(\sprintf('Directory "%s" was not created', $dstdir));
        }
        if ($curdir = \opendir($srcdir)) {
            while ($file = \readdir($curdir)) {
                if ('.' !== $file && '..' !== $file) {
                    $srcfile = $srcdir . \DIRECTORY_SEPARATOR . $file;
                    $dstfile = $dstdir . \DIRECTORY_SEPARATOR . $file;
                    if (\is_file($srcfile)) {
                        $ow = \is_file($dstfile) ? \filemtime($srcfile) - \filemtime($dstfile) : 1;
                        if ($ow > 0) {
                            if ($verbose) {
                                echo "Copying '${srcfile}' to '${dstfile}'...";
                            }
                            if (\copy($srcfile, $dstfile)) {
                                \touch($dstfile, \filemtime($srcfile));
                                $num++;
                                if ($verbose) {
                                    echo "OK\n";
                                }
                                $success[] = $srcfile;
                            } else {
                                $errors[] = $srcfile;
                            }
                        }
                    } elseif (\is_dir($srcfile)) {
                        $num += self::dircopy($srcfile, $dstfile, $errors, $success, $verbose);
                    }
                }
            }
            \closedir($curdir);
        }
        return $num;
    }

    /**
     * @param      $destDir
     * @param      $srcFile
     * @param      $error
     * @param      $success
     * @param bool $move
     */
    public static function copyOrMoveFile($destDir, $srcFile, &$error, &$success, $move = false): void
    {
        $mess        = ConfService::getMessages();
        $destFile    = ConfService::getRootDir() . $destDir . '/' . \basename($srcFile);
        $realSrcFile = ConfService::getRootDir() . "/${srcFile}";
        if (!\file_exists($realSrcFile)) {
            $error[] = $mess[100] . $srcFile;
            return;
        }
        if ($realSrcFile === $destFile) {
            $error[] = $mess[101];
            return;
        }
        if (\is_dir($realSrcFile)) {
            $errors    = [];
            $succFiles = [];
            $dirRes    = self::dircopy($realSrcFile, $destFile, $errors, $succFiles);
            if (\count($errors) > 0) {
                $error[] = $mess[114];
                return;
            }
        } else {
            $res = \copy($realSrcFile, $destFile);
            if (1 !== $res) {
                $error[] = $mess[114];
                return;
            }
        }

        if ($move) {
            // Now delete original
            self::deldir($realSrcFile);
            // both file and dir
            $messagePart = $mess[74] . " ${destDir}";
            if ($destDir === '/' . ConfService::getRecycleBinDir()) {
                $messagePart = $mess[123] . ' ' . $mess[122];
            }
            if (isset($dirRes)) {
                $success[] = $mess[117] . ' ' . \basename($srcFile) . ' ' . $messagePart . " (${dirRes} " . $mess[116] . ') ';
            } else {
                $success[] = $mess[34] . ' ' . \basename($srcFile) . ' ' . $messagePart;
            }
        } elseif (isset($dirRes)) {
            $success[] = $mess[117] . ' ' . \basename($srcFile) . ' ' . $mess[73] . " ${destDir} (" . $dirRes . ' ' . $mess[116] . ')';
        } else {
            $success[] = $mess[34] . ' ' . \basename($srcFile) . ' ' . $mess[73] . " ${destDir}";
        }
    }
}
