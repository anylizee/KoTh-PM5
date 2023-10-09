<?php

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
 
namespace viriors\tick;

use viriors\arena\Arena;
use viriors\Loader;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class KothTick extends Task {

	private Loader $pl;
	private ?Player $king;
	private string $kingName;
	private Arena $arena;
	private int $captureTime;

	public function __construct(Loader $pl, Arena $arena) {
        $this->pl = $pl;
        $this->arena = $arena;
    }

    public function onRun() : void{
		if(isset($this->king) and $this->king->isOnline() and $this->arena->isInside($this->king)){
			if(time() - $this->captureTime >= $this->pl->CAPTURE_TIME){
				$this->pl->stopKoth($this->kingName);
			}
		}else{
			$this->king = null;
			$this->kingName = "...";
			$this->captureTime = time();
			$onlinePlayers = Server::getInstance()->getOnlinePlayers();
			shuffle($onlinePlayers);
			foreach($onlinePlayers as $player){
				if($this->arena->isInside($player)){
					$this->king = $player;
					$this->kingName = $player->getName();
					break;
				}
			}
		}

		$timeLeft = $this->pl->CAPTURE_TIME - (time() - $this->captureTime);
		$minutes = floor($timeLeft/60);
		$seconds = sprintf("%02d", ($timeLeft - ($minutes * 60)));

			foreach(Server::getInstance()->getOnlinePlayers() as $player) {
				$player->sendTip("§bKOTH: §c".$this->arena->getName() . "§r - §bTime: §c" . $minutes . ":" . $seconds . "\n§bKing: §c" . $this->kingName);
		    }
    }
}