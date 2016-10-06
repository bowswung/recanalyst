<?php

namespace RecAnalyst\Analyzers;

use RecAnalyst\GameInfo;

/**
 * Determine the game version that created the recorded game file.
 */
class VersionAnalyzer extends Analyzer
{
    /** @var int[] Trial game version IDs. */
    public $trialVersions = [
        GameInfo::VERSION_AOKTRIAL,
        GameInfo::VERSION_AOCTRIAL,
    ];

    /** @var int[] UserPatch game version IDs. */
    public $userpatchVersions = [
        GameInfo::VERSION_USERPATCH11,
        GameInfo::VERSION_USERPATCH12,
        GameInfo::VERSION_USERPATCH13,
        GameInfo::VERSION_USERPATCH14,
        GameInfo::VERSION_AOFE21,
    ];

    /** @var int[] Age of Kings game version IDs. */
    public $aokVersions = [
        GameInfo::VERSION_AOK,
        GameInfo::VERSION_AOKTRIAL,
        GameInfo::VERSION_AOK20,
        GameInfo::VERSION_AOK20A,
    ];

    /** @var int[] Age of Conquerors expansion game version IDs. */
    public $aocVersions = [
        GameInfo::VERSION_AOC,
        GameInfo::VERSION_AOCTRIAL,
        GameInfo::VERSION_AOC10,
        GameInfo::VERSION_AOC10C,
    ];

    /** @var int[] HD Edition game version IDs. */
    public $hdVersions = [
        GameInfo::VERSION_HD
    ];

    /**
     * Read recorded game version metadata.
     *
     * Recorded game version information is encoded as:
     *     char version[8]; // something like "VER 9.C\0"
     *     float subVersion;
     *
     * @return object
     */
    protected function run()
    {
        $versionString = rtrim($this->readHeaderRaw(8));
        $subVersion = round($this->readHeader('f', 4), 2);

        $version = $this->getVersion($versionString, $subVersion);

        $analysis = new \StdClass;
        $analysis->versionString = $versionString;
        $analysis->version = $version;
        $analysis->subVersion = $subVersion;

        $analysis->isTrial = in_array($version, $this->trialVersions);
        $analysis->isAoK = in_array($version, $this->aokVersions);
        $analysis->isAoC = in_array($version, array_merge($this->aocVersions, $this->userpatchVersions, $this->hdVersions));
        $analysis->isUserPatch = in_array($version, $this->userpatchVersions);
        $analysis->isHDEdition = in_array($version, $this->hdVersions);
        $analysis->isAoe2Record = $subVersion >= 12.36;

        $analysis->isMgz = $analysis->isUserPatch;
        $analysis->isMgx = $analysis->isAoC;
        $analysis->isMgl = $analysis->isAoK;

        return $analysis;
    }

    /**
     * Get the version ID from a version string and sub-version number.
     *
     * @param string  $version  Version string, found at the start of the file
     *     header.
     * @param float  $subVersion  Sub-version number.
     * @return int Game version ID.
     */
    private function getVersion($version, $subVersion)
    {
        switch ($version) {
            case 'TRL 9.3':
                return $this->isMgx ? GameInfo::VERSION_AOCTRIAL : GameInfo::VERSION_AOKTRIAL;
            case 'VER 9.3':
                return GameInfo::VERSION_AOK;
            case 'VER 9.4':
                if ($subVersion > 11.76) {
                    return GameInfo::VERSION_HD;
                }
                return GameInfo::VERSION_AOC;
            case 'VER 9.5':
                return GameInfo::VERSION_AOFE21;
            case 'VER 9.8':
                return GameInfo::VERSION_USERPATCH12;
            case 'VER 9.9':
                return GameInfo::VERSION_USERPATCH13;
            // UserPatch 1.4 RC 1
            case 'VER 9.A':
            // UserPatch 1.4 RC 2
            case 'VER 9.B':
            case 'VER 9.C':
            case 'VER 9.D':
                return GameInfo::VERSION_USERPATCH14;
            default:
                return $version;
        }
    }
}