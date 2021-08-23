<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Character;
use App\Setting;

class CharacterController extends Controller
{
    public function index()
    {
        $servers = session()->get('guilds');
        return view('characters.index')
        ->with('servers', $servers);
    }

    public function lookup($serverID)
    {
        $q = request()->query('q');
        if ($q) {
            $chararacter = Character::where('name', $q)->where('guildID', $serverID)->first();
            if ($character && empty($character->mainID)) {
                $character = Character::where('id', $character->mainID)->first();
            }
        }
    }

    public function server($serverID)
    {
        $currentServer = $this->getServer($serverID, false);

        $settings = Setting::where('guildID', $serverID)->first();
        if (!$this->goodBotInstalled($serverID)) {
            return redirect()->route('character.list', ['serverID' => $serverID]);
        }
        
        $characters = [];
        $classes = [
            'warrior', 'paladin', 'shaman', 'hunter', 'rogue', 'druid', 'priest', 'warlock', 'mage'
        ];
        $roles = [
            'dps', 'caster', 'tank', 'healer'
        ];
        $main = $this->getMain($currentServer->id);
        if (!empty($main)) {
            $characters[] = $main;
            $alts = Character::where('mainID', $main->id)->get();
            foreach ($alts as $alt) {
                $characters[] = $alt;
            }
        }
        

        return view('characters.server')
        ->with('nick', $this->getNick($serverID))
        ->with('server', $currentServer)
        ->with('characters', $characters)
        ->with('classes', $classes)
        ->with('roles', $roles)
        ->with('settings', $settings);
    }

    public function search($character, $server='', $region='') {

        if (empty($server)) {
            $server = 'Mankrik';
        }
        if (empty($region)) {
            $region = 'US';
        }
        $character = ucfirst(strtolower($character));

        $searchUrl = "https://goodbot.me/api/gear/" . $character . "/" . $server . "/" . $region . "?id=" . env('GOODBOT_ID') . "&key=" . env('GOODBOT_KEY');

        // init curl
        $ch = curl_init($searchUrl);

        // curl settings
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = json_decode(curl_exec($ch));

        if (property_exists($response, 'error')) {
            return ['error' => $response->error];
        }

        $itemList = [];
        foreach ($response->data AS $fight) {
            foreach ($fight->gear AS $item) {
                if (empty($itemList[$item->slot])) {
                    $itemList[$item->slot] = [];
                }
                $itemList[$item->slot][$item->id] = $item;
            }
        }

        return view('characters.search')
            ->with('itemList', $itemList)
            ->with('character', $character)
            ->with('wowServer', $server)
            ->with('region', $region);
    }

    public function save($serverID, $characterID) {
        // Retrieve the character name from the query
        $name = ucfirst(strtolower(request()->query('name')));
        // Retrieve the user's nickname on this server 
        $nick = $this->getNick($serverID);
        // Retrieve the user's main on this server
        $main = $this->getMain($serverID);

        // If the user has no main, and they've set a nickname other than their current nickname in the discord, change their nickname
        if ($name != $nick && empty($characterID) && empty($main)) {
            $result = $this->setNick($serverID, $name, true);
            if (is_object($result) && $result->code == 50013) {
                die('The bot could not automatically change your name due to permissions issues.  It must have a higher role within roles than the person it is trying to change.  Please note that it can never change the nickname of an administrator.<br />Please fix the permission issue, or manually change your name to "' . $name . '" and try again.');
            }
        }

        $class = request()->query('class');
        $role = request()->query('role');
        if (empty($name) || empty($class) || empty($role)) {
            return ['error' => 'invalid'];
        }
        $record = [
            'name' => $name,
            'class' => $class,
            'role' => $role,
            'memberID' => session()->get('user')->id
        ];

        // If characterID is empty, the person is attempting to create a new char.
        if (empty($characterID)) {
            // If the character already exists, it's just not set up as the player's alt.  Fix that.
            $existing = Character::where('name', $name)->where('guildID', $serverID)->first();
            if ($existing) {
                $record['mainID'] = $main->id;
                Character::where('id', $existing->id)->update($record);
            } else {
                $record['guildID'] = $serverID;
                if ($main) {
                    $record['mainID'] = $main->id;
                }
                Character::create($record);
            }
        } else {
            Character::where('id', $characterID)->update($record);
        }
        return ['success' => true];
    }

    public function setNick($serverID, $nick) {
        $user = session()->get('user');
        $url = '/guilds/' . $serverID . '/members/' . $user->id;
        $request = $this->botRequest($url, ['nick' => $nick], true);
        return $request;
    }

    public function getNick($serverID) {
        $user = session()->get('user');
        $userInfo = $this->botRequest('/guilds/' . $serverID . '/members/' . $user->id);
        $nick = null;
        if (property_exists($userInfo, 'user')) {
            $nick = property_exists($userInfo, 'nick') && !empty($userInfo->nick) ? $userInfo->nick : $userInfo->user->username;
        }
        return $nick;
    }

    public function getMain($serverID) {
        $nick = $this->getNick($serverID);

        if (!empty($nick)) {
            $main = Character::where('name', $nick)
                ->where('guildID', $serverID)
                ->first();
            return $main;
        }
        return false;
    }
}