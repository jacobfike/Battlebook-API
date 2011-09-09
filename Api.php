<?php

/**
* Battlebook_Service_Bnet_Api
* A simple wrapper for the Battle.net API released by Blizzard Entertainment for World of Warcraft
* 
* Author: Jacob Fike
*/
class Battlebook_Service_Bnet_Api
{
    const BASE_PATH = '/api/wow';
    
    const REGION_USA = 'us';
    const REGION_EUROPE = 'eu';
    const REGION_KOREA = 'kr';
    const REGION_TAIWAN = 'tw';
    const REGION_CHINA = 'cn';
    
    public static $HOST = array(
        Battlebook_Service_Bnet_Api::REGION_USA => "us.battle.net",
        Battlebook_Service_Bnet_Api::REGION_EUROPE => "eu.battle.net",
        Battlebook_Service_Bnet_Api::REGION_KOREA => "kr.battle.net",
        Battlebook_Service_Bnet_Api::REGION_TAIWAN => "tw.battle.net",
        Battlebook_Service_Bnet_Api::REGION_CHINA => "battlenet.com.cn"
    );
    
    public static $CHARACTER_FIELDS = array(
        'guild', // A summary of the guild that the character belongs to. If the character does not belong to a guild and this field is requested, this field will not be exposed.
        'stats', // A map of character attributes and stats.
        'talents', // A list of talent structures.
        'items', // A list of items equipted by the character. Use of this field will also include the average item level and average item level equipped for the character.
        'reputation', // A list of the factions that the character has an associated reputation with.
        'titles', // A list of the titles obtained by the character including the currently selected title.
        'professions', // A list of the character's professions. It is important to note that when this information is retrieved, it will also include the known recipes of each of the listed professions.
        'appearance', // A map of values that describes the face, features and helm/cloak display preferences and attributes.
        'companions', // A list of all of the non-combat pets obtained by the character.
        'mounts', // A list of all of the mounts obtained by the character.
        'pets', // A list of all of the combat pets obtained by the character.
        'achievements', // A map of achievement data including completion timestamps and criteria information.
        'progression', // A list of raids and bosses indicating raid progression and completedness.
        'pvp', // A map of pvp information including arena team membership and rated battlegrounds information.
        'quests', // A list of quests completed by the character.
    );
    
    public static $GUILD_FIELDS = array(
        'members', // A list of characters that are a member of the guild
        'achievements', // A set of data structures that describe the achievements earned by the guild.
    );
    
    private $options;
    
    function __construct(array $options = array())
    {
        if (empty($options)) {
            $options = array(
                'region' => Battlebook_Service_Bnet_Api::REGION_USA,
                'locale' => 'en_US'
            );
        }
        
        $this->options = $options;
    }
    
    public function setRegion($region)
    {
        $this->options['region'] = $region;
    }
    
    public function getRegion()
    {
        return $this->options['region'];
    }
    
    public function setLocale($locale)
    {
        $this->options['locale'] = $locale;
    }
    
    public function getLocale()
    {
        return $this->options['locale'];
    }
    
    public function getRealmStatus()
    {
        return $this->makeRequest('/realm/status');
    }
    
    public function getCharacter($name, $realm, $fields = array())
    {
        return $this->makeRequest('/character/' . $realm . '/' . $name, array(
            'fields' => implode(',', $fields)
        ));
    }
    
    public function getItem($itemid)
    {
        return $this->makeRequest('/item/' . $itemid);
    }
    
    public function getGuild($name, $realm, $fields = array())
    {
        return $this->makeRequest('/guild/' . $realm . '/' . $name, array(
            'fields' => implode(',', $fields)
        ));
    }
    
    public function getClasses()
    {
        return $this->makeRequest('/data/character/classes');
    }
    
    public function getRaces()
    {
        return $this->makeRequest('/data/character/races');
    }
    
    public function getGuildRewards()
    {
        return $this->makeRequest('/data/guild/rewards');
    }
    
    public function getGuildPerks()
    {
        return $this->makeRequest('/data/guild/perks');
    }
    
    public function getItemClasses()
    {
        return $this->makeRequest('/data/item/classes');
    }
    
    private function makeRequest($url, $params = array())
    {
        $client = new Zend_Http_Client('http://' . static::$HOST[$this->options['region']] . Battlebook_Service_Bnet_Api::BASE_PATH . $url);
        $client->setParameterGet($params);
        $response = $client->request();
        
        return json_decode($response->getBody());
    }
}
