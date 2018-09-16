<?php
/**
 * @name        FirstJoinBook
 * @main        sabone\FirstJoinBook
 * @version     1.0.0
 * @api         4.0.0
 * @author      SaBone(imaheejae@gmail.com)
 */
namespace sabone{
	use pocketmine\plugin\PluginBase;
	use pocketmine\event\Listener;
	use pocketmine\utils\Config;
	use pocketmine\event\player\PlayerJoinEvent;
	use pocketmine\Player;
	use pocketmine\item\{Item,WrittenBook};
	class FirstJoinBook extends PluginBase implements Listener{
		public $config;
		public $contents=[];
		public $item;
		function onEnable():void{
			$this->getServer()->getPluginManager()->registerEvents($this,$this);
			@mkdir($this->getDataFolder());
			$this->config=new Config($this->getDataFolder()."BookContent.txt",Config::ENUM);
			$this->contents=$this->config->getAll(true);
			if(count($this->contents)===0){
				$this->config->set("튜토리얼 책");
				$this->config->save();
				$this->contents=$this->config->getAll(true);
			}
		}
		function onJoin(PlayerJoinEvent $event):void{
			$player=$event->getPlayer();
			if(!$player->hasPlayedBefore()){
				if(!isset($this->item)){
					$this->item=$this->makeBook();
				}
				$player->getInventory()->addItem($this->item);
			}
		}
		function makeBook():Item{
			$item=Item::get(Item::WRITTEN_BOOK);
			$item->setTitle("Tutorial Book");
			$item->setGeneration(WrittenBook::GENERATION_ORIGINAL);
			$item->setAuthor("Admin");
			$item->setCustomName("Tutorial Book");
			$item->setLore(["Read This Book"]);
			$content=array();
			$con="";
			foreach($this->contents as $k=>$v){
				$con.=$v."\n";
				if(($k!==0&&$k%10===0)||$k===count($this->contents)-1){
					array_push($content,$con);
					$con="";
				}
			}
			foreach($content as $k=>$v){
				$item->setPageText($k,$v);
			}
			return $item;
		}
	}
}