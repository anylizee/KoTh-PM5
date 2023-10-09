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

use viriors\Loader;
use pocketmine\scheduler\Task;

class StartKothTask extends Task {

	public Loader $pl;

	public function __construct(Loader $pl) {
		$this->pl = $pl;
	}

	public function onRun() : void {
		if (in_array((float)date("G.i"), $this->pl->START_TIMES)){
			$this->pl->startKoth($this->pl->getArena());
		}
	}
}