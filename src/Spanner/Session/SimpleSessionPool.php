<?php
/**
 * Copyright 2016 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Google\Cloud\Spanner\Session;

/**
 * A simple session pool implementation.
 */
class SimpleSessionPool implements SessionPoolInterface
{
    /**
     * @var SessionClient
     */
    private $sessionClient;

    /**
     * @var Session[]
     */
    private $sessions = [];

    /**
     * @param SessionClient $sessionClient The session client.
     */
    public function __construct(SessionClient $sessionClient)
    {
        $this->sessionClient = $sessionClient;
    }

    /**
     * Retrieve a session from the pool.
     *
     * @param string $instance The instance name
     * @param string $database The database name
     * @param string $context The session context.
     * @param array $options Configuration Options.
     * @return Session
     */
    public function session($instance, $database, $mode, array $options = [])
    {
        if (!isset($this->sessions[$instance.$database.$mode])) {
            $this->sessions[$instance.$database] = $this->sessionClient->create($instance, $database, $options);
        }

        return $this->sessions[$instance.$database];
    }
}
