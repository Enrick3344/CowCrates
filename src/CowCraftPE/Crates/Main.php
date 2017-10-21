<?php

namespace CowCraftPE\Crates;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener{
  
  public function onEnable(){
    @mkdir($this->getDataFolder());
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
		$this->loadCommand();
		$this->loadConfig();
		$this->getLogger()->notice("CowCraftNetwork Crates Enabled!");;
	}
	
	public function onDisable(){
		$this->getLogger()->notice("CowCraftNetwork Crates Enabled!");
	}
	public function loadConfig(){
	  $this->saveResource("config.yml");
	  $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, array(
		  'Crates-Block' => 54,
      'Key-Item-Id' => 370,
      'Broadcast' => true,
      'BroadcastMessage' => "&6{player}&b Used a crate and got awesome rewards!",
      'Common' => array(
        'Items' => [],
        'Money' => 100,
        'Commands' => []),
      'Rare' => array(
        'Items' => [],
        'Money' => 300,
        'Commands' => []),
      'Legendary' => array(
        'Items' => [],
        'Money' => 500,
        'Commands' => [])
    ));
	  $this->config->save();
  }
	public function loadCommand(){
		$commands = [
		  "key" => new KeyCommand($this)
		];
		foreach($commands as $name => $class){
			$this->getServer()->getCommandMap()->register($name, $class);
		}
	}
	
	public function translateColors($string){
		$msg = str_replace("&1",TextFormat::DARK_BLUE,$string);
		$msg = str_replace("&2",TextFormat::DARK_GREEN,$msg);
		$msg = str_replace("&3",TextFormat::DARK_AQUA,$msg);
		$msg = str_replace("&4",TextFormat::DARK_RED,$msg);
		$msg = str_replace("&5",TextFormat::DARK_PURPLE,$msg);
		$msg = str_replace("&6",TextFormat::GOLD,$msg);
		$msg = str_replace("&7",TextFormat::GRAY,$msg);
		$msg = str_replace("&8",TextFormat::DARK_GRAY,$msg);
		$msg = str_replace("&9",TextFormat::BLUE,$msg);
		$msg = str_replace("&0",TextFormat::BLACK,$msg);
		$msg = str_replace("&a",TextFormat::GREEN,$msg);
		$msg = str_replace("&b",TextFormat::AQUA,$msg);
		$msg = str_replace("&c",TextFormat::RED,$msg);
		$msg = str_replace("&d",TextFormat::LIGHT_PURPLE,$msg);
		$msg = str_replace("&e",TextFormat::YELLOW,$msg);
		$msg = str_replace("&f",TextFormat::WHITE,$msg);
		$msg = str_replace("&o",TextFormat::ITALIC,$msg);
		$msg = str_replace("&l",TextFormat::BOLD,$msg);
		$msg = str_replace("&r",TextFormat::RESET,$msg);
		return $msg;
	
}
