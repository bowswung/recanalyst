<?php
/**
 * Defines Player class.
 *
 * @package recAnalyst
 */

namespace RecAnalyst;

/**
 * Class Player.
 *
 * Player implements a player in the game.
 * @package recAnalyst
 */
class Player {

    /**
     * Player's name.
     * @var string
     */
    public $name;

    /**
     * Player's index.
     * @var int
     */
    public $index;

    /**
     * Defines if the player is a human.
     * @var bool
     */
    public $human;

    /**
     * Defines player's team index (0 = no team).
     * @var int
     */
    public $team;

    /**
     * Defines if player is an owner of the game.
     * @var bool
     */
    public $owner;

    /**
     * Id of player's civilization.
     * @var int
     * @see Civilization
     */
    public $civId;

    /**
     * Id of player's color.
     * @var int
     */
    public $colorId;

    /**
     * Indicates if the player is cooping in the game.
     * @var bool true if player coops, otherwise false
     */
    public $isCooping;

    /**
     * Player's feudal time (in ms, 0 if hasn't been reached).
     * @var int
     */
    public $feudalTime;

    /**
     * Player's castle time (in ms).
     * @var int
     */
    public $castleTime;

    /**
     * Player's imperial time (in ms).
     * @var int
     */
    public $imperialTime;

    /**
     * Player's resign time (in ms) or 0 if player hasn't been resigned.
     * @var int
     */
    public $resignTime;

    /**
     * An array of player's researches.
     * An associative array containing "research id - time of research" pairs.
     * @var array
     */
    public $researches;

    /**
     * Player's initial state.
     * @var InitialState
     */
    public $initialState;

    /**
     * Class constructor.
     * @return void
     */
    public function __construct() {
         $this->name = '';
         $this->index = $this->team = $this->colorId = -1;
         $this->human = $this->owner = $this->isCooping = false;
         $this->civId = Civilization::NONE;
         $this->feudalTime = $this->castleTime = $this->imperialTime = $this->resignTime = 0;
         $this->researches = array();
         $this->initialState = new InitialState();
    }

    /**
     * Returns civilizaton string.
     * @return string
     */
    public function getCivString() {
        return isset(RecAnalystConst::$CIVS[$this->civId][0]) ?
            RecAnalystConst::$CIVS[$this->civId][0] : '';
    }

    /**
     * Returns whether the player is a human player.
     * @return bool True if human, false if AI.
     */
    public function isHuman() {
     return $this->human;
    }

    /**
     * Returns the index of the player's team in RecAnalyst::$teams.
     * @return int
     */
    public function getTeamID() {
     return $this->team;
    }

    /**
     * Returns the player's name.
     * @return string
     */
    public function getName() {
     return $this->name;
    }

    /**
     * Returns whether the player is co-oping.
     * @return bool
     */
    public function isCooping() {
     return $this->isCooping;
    }

    /**
     * Returns this player's feudal age advance time.
     * @return int
     */
    public function getFeudalTime() {
     return $this->feudalTime;
    }

    /**
     * Returns this player's castle age advance time.
     * @return int
     */
    public function getCastleTime() {
     return $this->castleTime;
    }

    /**
     * Returns this player's imperial age advance time.
     * @return int
     */
    public function getImperialTime() {
     return $this->imperialTime;
    }

    /**
     * Returns this player's resign time.
     * @return int
     */
    public function getResignTime() {
     return $this->resignTime;
    }
}