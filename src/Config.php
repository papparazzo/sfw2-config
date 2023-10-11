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

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use SFW2\Config\Exceptions\ContainerException;
use SFW2\Config\Exceptions\NotFoundException;

class Config implements ContainerInterface {

    protected const STRING_SEPARATOR = '.';

    protected array $values = [];

    /**
     * @throws ContainerException
     */
    public function __construct(array $values) {
        $this->values = $values;
    }

    public function getAsArray(): array {
        return $this->values;
    }

    public function get(string $id) {
        $tokens = explode(self::STRING_SEPARATOR, $id);
        $array = $this->values;
        foreach($tokens as $token) {
            if(!isset($array[$token])) {
                throw new NotFoundException();
            }
            $array = $array[$token];
        }
        return $array;
    }

    /**
     * @throws ContainerExceptionInterface
     */
    public function has(string $id): bool {
        try {
            $this->get($id);
        } catch(NotFoundExceptionInterface) {
            return false;
        }
        return true;
    }
}