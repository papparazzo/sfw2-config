<?php

/**
 *  SFW2 - SimpleFrameWork
 *
 *  Copyright (C) 2017  Stefan Paproth
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program. If not, see <http://www.gnu.org/licenses/agpl.txt>.
 *
 */

namespace SFW2\Config;

use Psr\Container\ContainerInterface;
use SFW2\Config\Exceptions\ContainerException;
use SFW2\Config\Exceptions\NotFoundException;

class Config implements ContainerInterface {

    protected const STRING_SEPARATOR = '.';

    protected array $conf = [];

    /**
     * @throws ContainerException
     */
    public function __construct(array $config) {
        $this->append($config);
    }

    public function getAsArray(): array {
        return $this->conf;
    }

    public function get(string $id) {
        if(!$this->has($id)) {
            throw new NotFoundException();
        }
        return $this->conf[$id];
    }

    public function has(string $id): bool {
        return isset($this->conf[$id]);
    }

    /**
     * @param array $values
     * @return void
     * @throws ContainerException
     */
    protected function append(array $values): void {
        foreach($values as $key => $items) {
            if(!is_array($items)) {
                throw new ContainerException("invalid structure given");
            }
            if(!is_string($key)) {
                throw new ContainerException("only associative arrays allowed!");
            }
            foreach($items as $id => $item) {
                $this->conf[$key . self::STRING_SEPARATOR . $id] = $item;
            }
        }
    }
}