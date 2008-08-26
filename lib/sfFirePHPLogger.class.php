<?php

/* ***** BEGIN LICENSE BLOCK *****
 *
 * This file is part of sfFirePHPLoggerPlugin.
 *
 * Software License Agreement (New BSD License)
 *
 * Copyright (c) 2006-2008, Christoph Dorn
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright notice,
 *       this list of conditions and the following disclaimer.
 *
 *     * Redistributions in binary form must reproduce the above copyright notice,
 *       this list of conditions and the following disclaimer in the documentation
 *       and/or other materials provided with the distribution.
 *
 *     * Neither the name of Christoph Dorn nor the names of its
 *       contributors may be used to endorse or promote products derived from this
 *       software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * ***** END LICENSE BLOCK ***** */

 /*
  * $Date$
  * $Author$
  * $Rev$
  */

/**
 * Pluggin that integrate the FirePHP firexof extension to the symfony framework.
 *
 * @copyright   Copyright (C) 2008-2008 Courbon Thomas
 * @author      Courbon Thomas <thomas@cafeaumiel.com> http://blog.cafeaumiel.com
 * @license     http://www.opensource.org/licenses/bsd-license.php
 */

class sfFirePHPLogger extends sfLogger
{
  protected
    $FirePHP = null;

  /*
   * Constructor that load the FirePHP class
   *
   * Options :
   *    load_function:
   *      type: boolean
   *      default: false
   *      description: if true will attempt to load the fb function.
   */
  public function __construct(sfEventDispatcher $dispatcher, $options = array())
  {
    parent::__construct($dispatcher, $options);
    $this->FirePHP = FirePHP::getInstance(true);

    if (!is_callable('fb') && isset($options['load_function']) && $options['load_function'])
      require(dirname(__FILE__).DIRECTORY_SEPARATOR.'FirePHPCore'.DIRECTORY_SEPARATOR.'fb.php');
  }

  /*
   * Accessor for the FirePHP instance.
   */
  public function getInstance()
  {
    return $this->FirePHP;
  }

  /**
   * Logs a message.
   *
   * Priority tables :
   *    sfLogger::EMERG   =>  FirePHP::ERROR;
   *    sfLogger::ALERT   =>  FirePHP::ERROR;
   *    sfLogger::CRIT    =>  FirePHP::ERROR;
   *    sfLogger::ERR     =>  FirePHP::ERROR;
   *    sfLogger::WARNING =>  FirePHP::WARN;
   *    sfLogger::NOTICE  =>  FirePHP::WARN;
   *    sfLogger::INFO    =>  FirePHP::INFO;
   *    sfLogger::DEBUG   =>  FirePHP::LOG;
   *
   * @param string $message   Message
   * @param string $priority  Message priority
   */
  protected function doLog($message, $priority)
  {
    if (!headers_sent())
    {
      switch($priority)
      {
        case sfLogger::EMERG :
          $fbPriority = FirePHP::ERROR;
          break;
        case sfLogger::ALERT :
          $fbPriority = FirePHP::ERROR;
          break;
        case sfLogger::CRIT :
          $fbPriority = FirePHP::ERROR;
          break;
        case sfLogger::ERR :
          $fbPriority = FirePHP::ERROR;
          break;
        case sfLogger::WARNING :
          $fbPriority = FirePHP::WARN;
          break;
        case sfLogger:: NOTICE:
          $fbPriority = FirePHP::WARN;
          break;
        case sfLogger::INFO :
          $fbPriority = FirePHP::INFO;
          break;
        case sfLogger::DEBUG :
          $fbPriority = FirePHP::LOG;
          break;

      }
      $this->FirePHP->fb('('.$this->getPriorityName($priority).') : '.$message, $fbPriority);
    }
  }

}
