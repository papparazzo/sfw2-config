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

    protected const STRING_SEPARATOR = ':';

    protected array $conf = [];

    /**
     * @throws \SFW2\Config\Exceptions\ContainerException
     */
    public function __construct(array $config) {
        $this->append($config);
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
     * @throws \SFW2\Config\Exceptions\ContainerException
     */
    protected function append(array $values, string $id = '') {
        foreach ($values as $key => $item) {
            if(!is_string($key)) {
                throw new ContainerException("only associative arrays allowed!");
            }
            if(is_array($item)) {
                $this->append($item, $id . $key . self::STRING_SEPARATOR);
                continue;
            }
            $this->conf[$id . $key] = $item;
        }
    }

}