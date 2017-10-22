<?php

namespace CowCraftPE\Crates;

use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\level\sound\PopSound;
use pocketmine\utils\Config;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\level\particle\LavaParticle;
use pocketmine\level\particle\DustParticle;
use pocketmine\level\particle\PortalParticle;
use onebone\economyapi\EconomyAPI;
use pocketmine\math\Vector3;
use pocketmine\level\Level;

class Main extends PluginBase implements Listener{
  
  public function onEnable(){
    @mkdir($this->getDataFolder());
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
		$this->loadConfig();
		$this->getLogger()->notice("CowCraftNetwork Crates Enabled!");;
	}
	
	public function onDisable(){
		$this->getLogger()->notice("CowCraftNetwork Crates Enabled!");
	}
	public function loadConfig(){
	  $this->saveResource("config.yml");
	  $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, array(
	  'Crate-Level' => "world",
	  'Crates-Block' => 54,
      'Key-Item-Id' => 370,
      'Common-Items' => [],
	  'Common-Money' => 100,
	  'Common-Commands' => [],
      'Rare-Items' => [],
	  'Rare-Money' => 300,
	  'Rare-Commands' => [],
      'Legendary-Items' => [],
	  'Legendary-Money' => 500,
	  'Legendary-Commands' => []
    ));
	  $this->config->save();
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
	
	public function onInteract(PlayerInteractEvent $event){
		$crate = $event->getBlock();
		$crateworld = $this->config->get("Crate-Level");
		$block = $this->config->get("Crates-Block");
		$key = $this->config->get("Key-Item-Id");
		$player = $event->getPlayer();
		$name = $player->getName();
		$commonm = $this->config->get["Common-Money"];
		$commoni = $this->config->get["Common-Items"];
		$commonc = $this->config->get["Common-Commands"];
		$rarem = $this->config->get["Rare-Money"];
		$rarei = $this->config->get["Rare-Items"];
		$rarec = $this->config->get["Rare-Commands"];
		$legendarym = $this->config->get["Legendary-Money"];
		$legendaryi = $this->config->get["Legendary-Items"];
		$legendaryc = $this->config->get["Legendary-Commands"];
		$level = $player->getLevel();
		if($player->getLevel()->getName() === $crateworld){
		if($event->getBlock()->getId() == $block){
		if($event->getItem()->getId() == $key){
			 $event->setCancelled(); 
        		$x = $crate->getX();
        		$y = $crate->getY();
        		$z = $crate->getZ();
        		$r = 2000;
        		$g = 2000;
        		$b = 2000;
        		$center = new Vector3($x, $y, $z);
       		 	$radius = 200000;
        		$count = 100000;
				$commonparticle = new LavaParticle($center, $r, $g, $b, 1);
				$rareparticle = new PortalParticle($center, $r, $g, $b, 1);
				$legendaryparticle = new DustParticle($center, $r, $g, $b, 1);
				$player->sendMessage(TextFormat::GOLD . ">" . TextFormat::AQUA . "Opening a Crate...");
        		$prize = rand(1,3);
					switch($prize){
						case 1: //Common
							EconomyAPI::getInstance()->addMoney($name, $this->config->get("Common-Money"));
							foreach($this->config->get("Common-Items") as $items){
								$player->getInventory()->addItem(Item::get($items));
							}
							foreach($this->config->get("Common-Commands") as $cmd){
								$this->getServer()->dispatchCommand(new ConsoleCommandSender(), str_replace("{player}", $event->getPlayer()->getName(), $cmd));
							}
							for($yaw = 1000, $y = $center->y; $y < $center->y + 10; $yaw += (M_PI * 2) / 50, $y += 1 / 50){
								$x = cos($yaw) + $center->x;
								$z = cos($yaw) + $center->z;
								$commonparticle->setComponents($x, $y, $z);
								$level->addParticle($commonparticle);
							}
							$player->sendMessage("§l§7[§6CowCraftPE§7]§r§6 You Opened A Common Crate! Look In Your Inventory For Your Rewards");
							$player->getInventory()->removeItem(Item::get($key));
							$level->addSound(new PopSound(new Vector3($x, $y + 1, $z)));
							break;
						case 2: //Rare
							EconomyAPI::getInstance()->addMoney($name, $this->config->get("Rare-Money"));
							foreach($this->config->get("Rare-Items") as $items){
								$player->getInventory()->addItem(Item::get($items));
							}
							foreach($this->config->get("Rare-Commands") as $cmd){
								$this->getServer()->dispatchCommand(new ConsoleCommandSender(), str_replace("{player}", $event->getPlayer()->getName(), $cmd));
							}
							for($yaw = 1000, $y = $center->y; $y < $center->y + 10; $yaw += (M_PI * 2) / 50, $y += 1 / 50){
								$x = cos($yaw) + $center->x;
								$z = cos($yaw) + $center->z;
								$rareparticle->setComponents($x, $y, $z);
								$level->addParticle($rareparticle);
							}
							$player->sendMessage("§l§7[§6CowCraftPE§7]§r§a You Opened A Rare Crate! Look In Your Inventory For Your Rewards");
							$player->getInventory()->removeItem(Item::get($key));
							$level->addSound(new PopSound(new Vector3($x, $y + 1, $z)));
							break;
						case 3: //Legendary
							EconomyAPI::getInstance()->addMoney($name, $this->config->get("Legendary-Money"));
							foreach($this->config->get("Legendary-Items") as $items){
								$player->getInventory()->addItem(Item::get($items));
							}
							foreach($this->config->get("Legendary-Commands") as $cmd){
								$this->getServer()->dispatchCommand(new ConsoleCommandSender(), str_replace("{player}", $event->getPlayer()->getName(), $cmd));
							}
							for($yaw = 1000, $y = $center->y; $y < $center->y + 10; $yaw += (M_PI * 2) / 50, $y += 1 / 50){
								$x = cos($yaw) + $center->x;
								$z = cos($yaw) + $center->z;
								$legendaryparticle->setComponents($x, $y, $z);
								$level->addParticle($legendaryparticle);
							}							
							$player->sendMessage("§l§7[§6CowCraftPE§7]§r§b You Opened A Legendary Crate! Look In Your Inventory For Your Rewards");
							$player->getInventory()->removeItem(Item::get($key));
							$level->addSound(new PopSound(new Vector3($x, $y + 1, $z)));
							break;
					}
		}
		}
		}
	}
}