<?php defined('ROOTPATH') OR die('No direct access allowed.');
/*
 * $Id: MySQLiPreparedStatement.php 367 2011-08-12 10:39:28Z gekosale $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information please see
 * <http://creole.phpdb.org>.
 */

require_once ROOTPATH.'lib/creole/PreparedStatement.php';
require_once ROOTPATH.'lib/creole/common/PreparedStatementCommon.php';

/**
 * MySQLi implementation of PreparedStatement.
 *
 * @author    Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @version   $Revision: 367 $
 * @package   creole.drivers.mysqli
 */
class MySQLiPreparedStatement extends PreparedStatementCommon implements PreparedStatement {
    /**
     * Quotes string using native MySQL function.
     * @param string $str
     * @return string
     */
protected function escape($str)
    {
        //return str_replace("\\\'", "\0\'", mysqli_real_escape_string($this->getConnection()->getResource(), preg_replace("/(\015)/", "\\", $str)));
        while( strstr($str, '\\') !== FALSE){
    		$str = stripslashes($str);
    	}
        return mysqli_real_escape_string($this->getConnection()->getResource(), $str);
    }
}
