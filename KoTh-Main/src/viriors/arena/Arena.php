<?php

declare(strict_types=1);

namespace viriors\arena;

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

use viriors\Loader;

use pocketmine\player\Player;
use pocketmine\Server;

class Arena {

	private string $name;
	private array $data;

	public function __construct(string $arenaName){
		$this->name = $arenaName;

		$data = Loader::$instance->data->get($arenaName, null);
		if (is_null($data)) {
			$this->data = [
				"spawn" => null,
				"arenaMin" => [
					"x" => 0,
					"y" => 0,
					"z" => 0,
				],
				"arenaMax" => [
					"x" => 0,
					"y" => 0,
					"z" => 0,
				],
				"world" => Server::getInstance()->getWorldManager()->getDefaultWorld()->getFolderName(),
				"coords" => "0, 0, 0"
			];
			$this->save();
		} else {
			$this->data = $data;
		}
	}

	public function getName() : string {
		return $this->name;
	}

	public function getSpawn() : ?array {
		return $this->data["spawn"];
	}

	public function getCoords() : string {
		return $this->data["coords"];
	}

	public function getWorld() : string {
		return $this->data["world"];
	}

	public function getMin() : array {
		return $this->data["arenaMin"];
	}

	public function getMax() : array {
		return $this->data["arenaMax"];
	}



	public function setCoords(string $coords) {
		$this->data["coords"] = $coords;
		$this->save();
	}

	public function setSpawn(?array $spawn) {
		$this->data["spawn"] = $spawn;
		$this->save();
	}

	public function setWorld(string $worldName) {
		$this->data["world"] = $worldName;
		$this->save();
	}

	public function setMin(array $arenaMin) {
		$this->data["arenaMin"] = $arenaMin;
		$this->save();
	}

	public function setMax(array $arenaMax) {
		$this->data["arenaMax"] = $arenaMax;
		$this->save();
	}



	public function save() {
		$data = Loader::$instance->data;
		$data->set($this->name, $this->data);
		$data->save();
	}

	public function isInside(Player $player) : bool {
		$min = $this->getMin();
		$max = $this->getMax();
		if ($player->getWorld()->getFolderName() == $this->getWorld() AND
			$player->isOnline() AND
			$player->getPosition()->getX() >= $min["x"] AND $player->getPosition()->getX() <= $max["x"] AND
			$player->getPosition()->getY() >= $min["y"] AND $player->getPosition()->getY() <= $max["y"] AND
			$player->getPosition()->getZ() >= $min["z"] AND $player->getPosition()->getZ() <= $max["z"]) {
			return True;
		}
		return False;
	}
}