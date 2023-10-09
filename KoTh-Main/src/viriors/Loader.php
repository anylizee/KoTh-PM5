<?php

namespace viriors;

/**
 * MIT License
 * 
 * Copyright (c) 2023 viriors
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 **/

use viriors\tick\KothTick;
use viriors\tick\StartKothTick;
use viriors\command\KothCommand;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\Config;

class Loader extends PluginBase {
  use SingletonTrait;
  
  /** @var $data **/
  public Config $data;
  /** @var $task **/
	public ?TaskHandler $task;
	/** @var $current **/
	public ?Arena $current;
	/** @var $arenas **/
	public array $arenas;
	
	public int $TASK_DELAY;
	public int $CAPTURE_TIME;
	public array $START_TIMES;
	public array $REWARD_COMMANDS;
  
  protected function onLoad() : void {
    self::setInstance($this);
  }
  
  protected function onEnable() : void {

    $this->saveResource("config.yml");
    		$this->saveResource("data.yml");
    		$config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    		$this->data = new Config($this->getDataFolder() . "data.yml", Config::YAML);
    		$this->START_TIMES = $config->get("times", []);
    $this->REWARD_COMMANDS = $config->get("commands", ["give {player} diamond 64", "say {player}"]);
    		foreach ($this->data->getAll() as $arenaName => $arenaData) {
    			$this->arenas[$arenaName] = new Arena($arenaName);
    		}
    $this->getScheduler()->scheduleRepeatingTask(new StartKothTick($this), 600);
    $this->getServer()->getCommandMap()->register("/koth", new KothCommand());
  }
  
  public function isRunning() : bool {
		if (isset($this->task)) return True;
		else return False;
	}
	
	public function getArena(string $arenaName = null) : Arena|bool {
		if (is_null($arenaName)) {
			if (!empty($this->data->getAll())) $arenaName = array_rand($this->data->getAll());
			else return False;
		}
		if (!$this->data->get($arenaName)) {
			return False;
		}
		return $this->arenas[$arenaName];
	}
	
	public function startKoth(Arena $arena) : string {
		if ($this->isRunning()) return "§7[§3KoTh§7] is Running.";
		$this->task = $this->getScheduler()->scheduleRepeatingTask(new KothTick($this, $arena), 2);
		$this->current = $arena;
		$arenaName = $arena->getName();
		return "§7[§3KoTh§7] is started.";
	}
	
	public function stopKoth(string $winnerName = null) : string {
		if (!$this->isRunning()) return "§7[§bKOTH§7] §cThere is no KOTH events currently running";
		if (isset($winnerName)) {
			$consoleCommandSender = new ConsoleCommandSender($this->getServer(), $this->getServer()->getLanguage());
			foreach ($this->REWARD_COMMANDS as $command){
				$this->getServer()->dispatchCommand($consoleCommandSender, str_replace("{player}", $winnerName, $command));
			}
		} else {
			$winnerName = "no one";
		}
		$this->task->cancel();
		$this->task = null;
		$this->current = null;
		
		return "§7[§bKOTH§7] §aStopped KOTH";
	}
}
?>